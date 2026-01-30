<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\ReservationReviewRequest;
use App\Models\City;
use App\Services\Api\V1\BookingService;
use App\Services\Api\V1\HotelApiService;
use App\Services\Api\V1\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function __construct(
        protected HotelApiService $hotelApi,
        protected PaymentService $paymentService,
        protected BookingService $bookingService,
        protected \App\Services\LocationService $locationService
    ) {}

    public function search(Request $request)
    {
        set_time_limit(180);
        try {
            $hotelCodes = $request->input('HotelCodes');

            // Validate HotelCodes
            if (empty($hotelCodes)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'Status' => [
                            'Code' => 400,
                            'Description' => 'Hotel Codes can not be null or empty',
                        ],
                    ], 400);
                }

                // For standard requests, redirect back or show error
                return redirect()->back()->with('error', __('Invalid search parameters.'));
            }

            // Handle PaxRooms - can be array or single object
            $paxRooms = $request->input('PaxRooms');
            if (! is_array($paxRooms) || empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => $request->input('PaxRooms.0.Adults', $request->input('PaxRooms.Adults', 1)),
                        'Children' => $request->input('PaxRooms.0.Children', $request->input('PaxRooms.Children', 0)),
                        'ChildrenAges' => $request->input('PaxRooms.0.ChildrenAges', []),
                    ],
                ];
            }

            // Validate and get dates
            $checkIn = $request->input('CheckIn');
            $checkOut = $request->input('CheckOut');

            // Validate dates are not empty
            if (empty($checkIn) || empty($checkOut)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'Status' => [
                            'Code' => 400,
                            'Description' => 'CheckIn and CheckOut dates are required',
                        ],
                    ], 400);
                }

                return redirect()->back()->with('error', __('CheckIn and CheckOut dates are required.'));
            }

            $data = [
                'CheckIn' => $checkIn,
                'CheckOut' => $checkOut,
                'HotelCodes' => $hotelCodes,
                'GuestNationality' => $request->input('GuestNationality', 'SA'),
                'PaxRooms' => $paxRooms,
                'ResponseTime' => $request->input('ResponseTime', 18),
                'IsDetailedResponse' => $request->input('IsDetailedResponse', true),
                'Filters' => $request->input('Filters', [
                    'Refundable' => false,
                    'NoOfRooms' => 1,
                    'MealType' => 'All',
                ]),
            ];

            // Log request data for debugging
            Log::info('Hotel search request', $data);


            // Use AvailabilityService (single source of truth)
            $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);
            
                $availability = $availabilityService->checkAvailability(
                    $hotelCodes,
                    $checkIn,
                    $checkOut,
                    $paxRooms,
                    $request->input('GuestNationality', 'SA')
                );

            if (!$availability->isAvailable()) {
                return response()->json([
                    'Status' => [
                        'Code' => 200, // TBO often returns 200 even with 0 results
                        'Description' => 'No availability found',
                    ],
                    'HotelResult' => []
                ]);
            }

            // Construct TBO-compatible response for frontend
            // Since AvailabilityService might combine rooms from multiple hotels, we group them back
            $hotelResults = [];
            $roomsByHotel = [];
            
            foreach ($availability->rooms as $room) {
                // Determine hotel code (usually passed in BookingCode or TBO response)
                // In search results, TBO rooms usually have a HotelCode or we can infer it
                $hCode = $room['HotelCode'] ?? $hotelCodes; // Fallback to requested code if singular
                if (!isset($roomsByHotel[$hCode])) {
                    $roomsByHotel[$hCode] = [];
                }
                $roomsByHotel[$hCode][] = $room;
            }

            foreach ($roomsByHotel as $hCode => $hRooms) {
                $hotelResults[] = [
                    'HotelCode' => $hCode,
                    'Currency' => $availability->currency,
                    'Rooms' => $hRooms
                ];
            }

            return response()->json([
                'Status' => [
                    'Code' => 200,
                    'Description' => 'Successful',
                ],
                'HotelResult' => $hotelResults
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to search hotels: '.$e->getMessage());

            return response()->json([
                'Status' => [
                    'Code' => 500,
                    'Description' => __('Failed to search hotels'),
                ],
            ], 500);
        }
    }

    public function getHotels($cityCode)
    {
        try {
            Log::info("getHotels: Fetching for CityCode: $cityCode");

            // Get all hotels from all pages
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $response = $this->hotelApi->getAllCityHotels($cityCode, true, $language);

            $hotels = $response['Hotels'] ?? [];
            Log::info('getHotels: Received '.count($hotels).' hotels from API');

            if (! is_array($hotels)) {
                $hotels = json_decode(json_encode($hotels), true);
            }

            // Apply robust Hotel Name Translation if Arabic
            if ($language === 'ar' && ! empty($hotels)) {
                $translator = new \App\Services\HotelTranslationService;
                // Pass count to allow translating more than 1 hotel if needed (within service limits)
                $hotels = $translator->translateHotels($hotels, count($hotels));
            }

            $formattedHotels = collect($hotels)->map(function ($hotel) {
                return [
                    'HotelCode' => $hotel['HotelCode'] ?? $hotel['Code'] ?? '',
                    'HotelName' => $hotel['HotelName'] ?? $hotel['Name'] ?? '',
                    'HotelRating' => $hotel['HotelRating'] ?? '',
                    'Address' => $hotel['Address'] ?? '',
                    'CityName' => $hotel['CityName'] ?? '',
                    'CountryName' => $hotel['CountryName'] ?? '',
                    'ImageUrl' => isset($hotel['ImageUrls'][0]['ImageUrl']) ? $hotel['ImageUrls'][0]['ImageUrl'] : '',
                ];
            })->filter(function ($hotel) {
                return ! empty($hotel['HotelCode']);
            });

            Log::info('getHotels: Returning '.$formattedHotels->count().' formatted hotels');

            return response()->json($formattedHotels->values());
        } catch (\Exception $e) {
            Log::error('Failed to fetch hotels from TBO API for city '.$cityCode.': '.$e->getMessage());

            return response()->json(['error' => __('Failed to fetch hotels')], 500);
        }
    }

    public function getCityHotels($cityCode)
    {
        set_time_limit(180);
        try {
            // Get current page from request
            $page = (int) request('page', 1);
            $perPage = 12; // Hotels per page

            // Get City from DB for name verification
            $cityParam = City::where('code', $cityCode)->with('country')->first();
            $cityNames = [];

            // Default to session value or SA, then override if city implies specific country
            $selectedCountryCode = session('selected_country', 'SA');

            if ($cityParam) {
                // Normalize names for comparison (lowercase)
                if ($cityParam->name_en) {
                    $cityNames[] = strtolower($cityParam->name_en);
                }
                if ($cityParam->name_ar) {
                    $cityNames[] = strtolower($cityParam->name_ar);
                }
                // Add city name part if it contains comma (e.g. "Dubai, UAE" -> "dubai")
                foreach ($cityNames as $name) {
                    if (str_contains($name, ',')) {
                        $parts = explode(',', $name);
                        $cityNames[] = trim($parts[0]);
                    }
                }

                if ($cityParam->country) {
                    $selectedCountryCode = $cityParam->country->code;
                    // Update session to reflect the country of the selected city
                    session(['selected_country' => $selectedCountryCode]);
                }
            }

            // Cache key for the FULL LIGHTWEIGHT LIST
            // We fetch ALL hotels lightweight (no images) to allow accurate filtering and sorting
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $cacheKey = 'city_hotels_light_'.$cityCode.'_'.$language;

            // Cache for 24 hours
            $allLightweightHotels = Cache::remember($cacheKey, 86400, function () use ($cityCode, $language) {
                try {
                    // Fetch lightweight list (IsDetailedResponse=false)
                    $response = $this->hotelApi->getAllCityHotels($cityCode, false, $language);

                    $hotels = $response['Hotels'] ?? [];
                    if (! is_array($hotels)) {
                        $hotels = json_decode(json_encode($hotels), true);
                    }

                    // Update local DB with hotels count for popularity tracking (Self-Learning)
                    if (! empty($hotels)) {
                        try {
                            City::where('code', $cityCode)->update(['hotels_count' => count($hotels)]);
                        } catch (\Exception $e) {
                            Log::warning('Failed to update hotels_count for city code: '.$cityCode);
                        }
                    }

                    return $hotels;
                } catch (\Exception $e) {
                    Log::error('Failed to fetch city hotels from API: '.$e->getMessage());

                    return [];
                }
            });

            // -------------------------------------------------------------------------
            // AVAILABLE SEARCH (TBO) - User filter
            // -------------------------------------------------------------------------
            // If request has Date + Pax info, we MUST filter by actual availability from TBO
            $checkIn = request('CheckIn') ?? request('check_in');
            $checkOut = request('CheckOut') ?? request('check_out');

            Log::info('getCityHotels: Request parameters', [
                'CheckIn' => $checkIn,
                'CheckOut' => $checkOut,
                'PaxRooms' => request('PaxRooms'),
                'all_params' => request()->all(),
            ]);

            // Parse PaxRooms
            $paxRooms = request('PaxRooms');

            // Backward Compatibility / Fallback
            if (empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => (int) (request('PaxRooms.0.Adults') ?? request('adults') ?? 1),
                        'Children' => (int) (request('PaxRooms.0.Children') ?? request('children') ?? 0),
                        'ChildrenAges' => request('PaxRooms.0.ChildrenAges') ?? request('children_ages') ?? [],
                    ],
                ];
            }

            // Ensure correct structure for API with strict validation
            if (is_array($paxRooms)) {
                $cleanedPaxRooms = [];
                foreach ($paxRooms as $room) {
                    $adults = filter_var($room['Adults'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
                    $children = filter_var($room['Children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);

                    $childrenAges = $room['ChildrenAges'] ?? [];
                    if (! is_array($childrenAges)) {
                        $childrenAges = [];
                    }

                    // Cast ages to integers and clamp to 0-12
                    $childrenAges = array_map(function ($age) {
                        $ageInt = (int) $age;

                        return max(0, min(12, $ageInt));
                    }, $childrenAges);

                    // strict sync: Children count MUST match ChildrenAges length
                    if ($children > count($childrenAges)) {
                        for ($i = count($childrenAges); $i < $children; $i++) {
                            $childrenAges[] = 0;
                        }
                    } elseif ($children < count($childrenAges)) {
                        $childrenAges = array_slice($childrenAges, 0, $children);
                    }

                    $cleanedPaxRooms[] = [
                        'Adults' => $adults,
                        'Children' => $children,
                        'ChildrenAges' => $childrenAges,
                    ];
                }
                $paxRooms = $cleanedPaxRooms;
            }

            if ($checkIn && $checkOut && ! empty($paxRooms)) {
                // Validate Dates
                try {
                    $inDate = \Carbon\Carbon::parse($checkIn);
                    $outDate = \Carbon\Carbon::parse($checkOut);
                    $today = \Carbon\Carbon::today();

                    Log::info('getCityHotels: Availability search params', [
                        'checkIn' => $checkIn,
                        'checkOut' => $checkOut,
                        'paxRooms' => $paxRooms,
                        'inDate' => $inDate->toDateString(),
                        'outDate' => $outDate->toDateString(),
                        'today' => $today->toDateString(),
                    ]);

                    if ($outDate->lte($inDate)) {
                        Log::warning('Search skipped: CheckOut must be after CheckIn');
                        $allLightweightHotels = [];
                    } elseif ($inDate->lt($today)) {
                        Log::warning('Search skipped: CheckIn cannot be in the past');
                    } else {
                        Log::info('City Availability Search', ['city' => $cityCode, 'in' => $checkIn, 'out' => $checkOut, 'pax' => $paxRooms]);

                        // Filter by availability using AvailabilityService (City Search)
                        try {
                            $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);
                            
                            // 1. Get all codes from our city hotels list
                            $hotelCodes = array_column($allLightweightHotels, 'HotelCode');
                            
                            if (!empty($hotelCodes)) {
                                // 2. Fetch available hotels using BATCH call
                                $availableHotelsMap = $availabilityService->checkBatchAvailability(
                                    $hotelCodes,
                                    $checkIn,
                                    $checkOut,
                                    $paxRooms,
                                    request('GuestNationality', 'SA'),
                                    false // Lightweight mode
                                );

                                // 3. STRICT FILTERING: Only keep hotels found in TBO with Price > 0
                                $filteredByAvailability = [];
                                foreach ($allLightweightHotels as $hotel) {
                                    $hotelCode = (string)$hotel['HotelCode'];
                                    
                                    if (isset($availableHotelsMap[$hotelCode])) {
                                        $result = $availableHotelsMap[$hotelCode];
                                        if ($result->isAvailable() && $result->minPrice > 0) {
                                            $hotel['MinPrice'] = $result->minPrice;
                                            $hotel['Currency'] = $result->currency;
                                            $hotel['IsSearchResult'] = true;
                                            $filteredByAvailability[] = $hotel;
                                        }
                                    }
                                }

                                Log::info('Search Filter Complete: found '.count($filteredByAvailability).' available hotels out of '.count($allLightweightHotels));
                                $allLightweightHotels = $filteredByAvailability;
                            }
                        } catch (\Exception $e) {
                            Log::error('City Availability Search Failed: '.$e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Date parsing error: '.$e->getMessage());
                }
            }
            // -------------------------------------------------------------------------

            // STRICT FILTERING
            // Filter hotels to ensure they actually belong to the requested city
            $filteredHotels = [];

            // Special handling for 6th of October (Code 100639) if names are missing
            if ($cityCode == '100639' && empty($cityNames)) {
                $cityNames = ['october', '6th', 'wahat'];
            }

            if (! empty($cityNames)) {
                foreach ($allLightweightHotels as $hotel) {
                    $hotelCityName = strtolower($hotel['CityName'] ?? '');
                    $hotelAddress = strtolower($hotel['Address'] ?? '');

                    $match = false;
                    foreach ($cityNames as $validName) {
                        if (empty($validName)) {
                            continue;
                        }

                        // Check if API CityName contains our valid name
                        if (str_contains($hotelCityName, $validName)) {
                            $match = true;
                            break;
                        }
                        // Fallback: Check address
                        if (str_contains($hotelAddress, $validName)) {
                            $match = true;
                            break;
                        }
                    }

                    if ($match) {
                        $filteredHotels[] = $hotel;
                    }
                }
            }

            // Safety Fallback: If strict filtering removed EVERYTHING, it might contain a bug or data mismatch.
            // In that case, return the original list to avoid showing empty page (unless the city really has no hotels).
            if (empty($filteredHotels) && ! empty($allLightweightHotels)) {
                // Log the potential issue
                Log::warning("Strict filtering returned 0 results for CityCode: $cityCode. Falling back to original list.", [
                    'valid_names' => $cityNames,
                ]);
                $filteredHotels = $allLightweightHotels;
            }

            // Calculate pagination on the FILTERED list
            $totalHotels = count($filteredHotels);
            $totalPages = (int) ceil($totalHotels / $perPage);
            $offset = ($page - 1) * $perPage;

            // Get hotels for current page
            $pagedHotels = array_slice($filteredHotels, $offset, $perPage);

            // Fetch DETAILS (Images, Amenities) ONLY for current page
            // We extract codes and make a single bulk call if possible, or individual calls
            // TBO's HotelDetails often takes 1 code, let's try comma separated or loop
            // Based on optimization, we'll assign details back to $pagedHotels

            $detailedHotels = [];
            if (! empty($pagedHotels)) {
                $hotelCodes = array_column($pagedHotels, 'HotelCode'); // or 'Code'
                $codesString = implode(',', $hotelCodes);

                // Cache details for this specific page/batch
                $detailsCacheKey = 'hotel_details_batch_'.md5($codesString).'_'.$language;

                $detailedHotelsMap = Cache::remember($detailsCacheKey, 86400, function () use ($codesString, $language) {
                    try {
                        $response = $this->hotelApi->getHotelDetails($codesString, $language);

                        // Handle response structure variations
                        $detailsList = $response['HotelDetails'] ?? $response['HotelResult'] ?? [];

                        // Map by code for easy lookup
                        $map = [];
                        if (is_array($detailsList)) {
                            foreach ($detailsList as $detail) {
                                $code = $detail['HotelCode'] ?? $detail['Code'] ?? '';
                                if ($code) {
                                    $map[$code] = $detail;
                                }
                            }
                        }

                        return $map;
                    } catch (\Exception $e) {
                        Log::error('Failed to fetch hotel details batch: '.$e->getMessage());

                        return [];
                    }
                });

                // Merge details into paged hotels
                foreach ($pagedHotels as &$hotel) {
                    $code = $hotel['HotelCode'] ?? $hotel['Code'] ?? '';
                    if (isset($detailedHotelsMap[$code])) {
                        // Merge detailed info (Images, Facilities, Description)
                        $hotel = array_merge($hotel, $detailedHotelsMap[$code]);
                    }
                }
                unset($hotel); // break reference
                $detailedHotels = $pagedHotels;
            }

            // Apply translation if needed
            if ($language === 'ar' && ! empty($detailedHotels)) {
                $translator = new \App\Services\HotelTranslationService;
                $detailedHotels = $translator->translateHotels($detailedHotels);
            }

            // Fetch all countries
            $countries = \App\Models\Country::select('id', 'code', 'name', 'name_ar')
                ->whereNotNull('code')
                ->where('code', '!=', '')
                ->orderBy('name', 'asc')
                ->get();

            // Ensure Cities are synced for this country
            // This fixes the issue where cities wouldn't show up if not visited on Home page first
            if ($selectedCountryCode) {
                $this->locationService->syncCitiesForCountry($selectedCountryCode);
            }

            // Fetch cities for sidebar (filtered by selected country)
            $cities = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->whereHas('country', function ($q) use ($selectedCountryCode) {
                    $q->where('code', $selectedCountryCode);
                })
                ->orderBy('name', 'asc')
                ->get();

            return view('Web.hotels', [
                'hotels' => $detailedHotels,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalHotels' => $totalHotels,
                'perPage' => $perPage,
                'cityCode' => $cityCode,
                'cities' => $cities,
                'countries' => $countries,
                'selectedCountryCode' => $selectedCountryCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch city hotels: '.$e->getMessage());

            $countries = \App\Models\Country::all();
            $cities = City::whereNotNull('code')->where('code', '!=', '')->orderBy('name', 'asc')->get();

            return view('Web.hotels', [
                'hotels' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalHotels' => 0,
                'perPage' => 12,
                'cityCode' => $cityCode,
                'cities' => $cities,
                'countries' => $countries,
                'selectedCountryCode' => 'SA',
            ]);
        }
    }

    public function getAllHotels()
    {
        set_time_limit(180);
        try {
            $request = request();

            // OPTIMIZED CITY FETCHING: Fetch more cities but process them incrementally
            $allCityCodes = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->orderBy('hotels_count', 'desc')
                ->limit(400) // Increase limit as we will load them in batches
                ->pluck('code')
                ->toArray();

            if (empty($allCityCodes)) {
                $allCityCodes = City::whereNotNull('code')->limit(30)->pluck('code')->toArray();
            }

            // Split into Initial Batch and Remaining
            // User requested ~500 hotels initially.
            // Assuming avg 10-15 hotels per city, 40 cities * 15 = 600.
            // We'll also increase max hotels per city in the API call below.
            $initialBatchSize = 40;
            $initialCityCodes = array_slice($allCityCodes, 0, $initialBatchSize);
            $remainingCityCodes = array_slice($allCityCodes, $initialBatchSize);

            $language = app()->getLocale();
            $hotels = [];

            if (! empty($initialCityCodes)) {
                // Cache key for the initial batch
                $cacheKey = 'hotels_initial_batch_v2_'.$language.'_'.md5(json_encode($initialCityCodes));
                $hotels = Cache::get($cacheKey);

                if (! $hotels) {
                    try {
                        $apiLang = $language === 'ar' ? 'ar' : 'en';

                        // Fetch initial batch
                        $response = $this->hotelApi->getHotelsFromMultipleCities(
                            $initialCityCodes,
                            true,
                            30, // Increased from 20 to 30 to get more hotels
                            $apiLang
                        );

                        $hotels = $response['Hotels'] ?? [];

                        Log::info('Initial Hotel Fetch Result:', [
                            'hotels_found' => count($hotels),
                            'cities_processed' => $response['CitiesProcessed'] ?? 0,
                        ]);

                        if (! is_array($hotels)) {
                            $hotels = json_decode(json_encode($hotels), true);
                        }

                        if (! empty($hotels)) {
                            Cache::put($cacheKey, $hotels, 86400); // 24 hours
                        } else {
                            Cache::put($cacheKey, [], 60);
                        }

                    } catch (\Exception $e) {
                        Log::error('Failed to fetch initial hotels from API: '.$e->getMessage());
                        $hotels = [];
                    }
                }

                // Apply robust Hotel Name Translation if Arabic
                if ($language === 'ar' && ! empty($hotels)) {
                    $translator = new \App\Services\HotelTranslationService;
                    $hotels = $translator->translateHotels($hotels);
                }
            }

            // -------------------------------------------------------------------------
            // AVAILABILITY FILTER - If user provides dates, filter hotels
            // -------------------------------------------------------------------------
            $checkIn = request('CheckIn');
            $checkOut = request('CheckOut');
            $paxRooms = request('PaxRooms');

            // If no dates provided, use today to tomorrow (1 night)
            // If no dates provided, use tomorrow to day after tomorrow (1 night)
            $isDefaultDates = false;
            if (empty($checkIn) || empty($checkOut)) {
                $checkIn = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                $checkOut = \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d');
                $isDefaultDates = true;
            }

            // Backward Compatibility
            if (empty($paxRooms) && request('adults')) {
                $paxRooms = [
                    [
                        'Adults' => (int) request('adults'),
                        'Children' => (int) request('children', 0),
                        'ChildrenAges' => [],
                    ],
                ];
            }

            // If no PaxRooms provided, default to 1 room with 2 adults
            if (empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => 2,
                        'Children' => 0,
                        'ChildrenAges' => [],
                    ],
                ];
            }

            // Clean PaxRooms
            if (is_array($paxRooms)) {
                $cleanedPaxRooms = [];
                foreach ($paxRooms as $room) {
                    $adults = filter_var($room['Adults'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
                    $children = filter_var($room['Children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);
                    $childrenAges = $room['ChildrenAges'] ?? [];
                    if (! is_array($childrenAges)) {
                        $childrenAges = [];
                    }
                    $childrenAges = array_map(function ($age) {
                        return max(0, min(12, (int) $age));
                    }, $childrenAges);
                    if ($children > count($childrenAges)) {
                        for ($i = count($childrenAges); $i < $children; $i++) {
                            $childrenAges[] = 0;
                        }
                    } elseif ($children < count($childrenAges)) {
                        $childrenAges = array_slice($childrenAges, 0, $children);
                    }
                    $cleanedPaxRooms[] = [
                        'Adults' => $adults,
                        'Children' => $children,
                        'ChildrenAges' => $childrenAges,
                    ];
                }
                $paxRooms = $cleanedPaxRooms;
            }

            if ($checkIn && $checkOut && ! empty($paxRooms) && ! empty($hotels)) {
                try {
                    $inDate = \Carbon\Carbon::parse($checkIn);
                    $outDate = \Carbon\Carbon::parse($checkOut);
                    $today = \Carbon\Carbon::today();

                    if ($outDate->gt($inDate) && $inDate->gte($today)) {
                        Log::info('All Hotels Availability Search', ['in' => $checkIn, 'out' => $checkOut, 'pax' => $paxRooms, 'total_hotels' => count($hotels)]);

                        // Limit to first 100 hotels for performance
                        $hotelsToCheck = array_slice($hotels, 0, 100);

                        // Process hotels in batches of 50
                        $batchSize = 50;
                        $hotelBatches = array_chunk($hotelsToCheck, $batchSize);
                        $availableHotels = [];

                        // Use AvailabilityService for consistent availability checking
                        $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);

                        foreach ($hotelBatches as $batchIndex => $batch) {
                            $batchCodes = array_column($batch, 'HotelCode');

                            if (! empty($batchCodes)) {
                                try {
                                    // Check availability for this batch
                                    $availabilityResults = $availabilityService->checkBatchAvailability(
                                        $batchCodes,
                                        $checkIn,
                                        $checkOut,
                                        $paxRooms,
                                        request('GuestNationality', 'SA'),
                                        false // Lightweight mode (Standard Search)
                                    );

                                    // Merge availability into hotel data
                                    foreach ($batch as $hotel) {
                                        $hotelCode = $hotel['HotelCode'];
                                        $result = $availabilityResults[$hotelCode] ?? null;

                                        if ($result && $result->isAvailable()) {
                                            $hotel['MinPrice'] = $result->minPrice;
                                            $hotel['Currency'] = $result->currency;
                                            $availableHotels[] = $hotel;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Batch '.$batchIndex.' Availability Search Failed: '.$e->getMessage());
                                }
                            }

                            // Add small delay between batches to avoid overwhelming API
                            if ($batchIndex < count($hotelBatches) - 1) {
                                usleep(100000); // 100ms delay
                            }
                        }

                        Log::info('Availability Filter Complete', ['checked' => count($hotels), 'available' => count($availableHotels), 'is_default' => $isDefaultDates]);

                        if ($isDefaultDates) {
                            // ENRICH ONLY: Merge prices into original $hotels list, but keep all hotels.
                            $priceMap = [];
                            foreach ($availableHotels as $availItem) {
                                $priceMap[$availItem['HotelCode']] = [
                                    'MinPrice' => $availItem['MinPrice'],
                                    'Currency' => $availItem['Currency'],
                                ];
                            }

                            foreach ($hotels as &$originalHotel) {
                                if (isset($priceMap[$originalHotel['HotelCode']])) {
                                    $originalHotel['MinPrice'] = $priceMap[$originalHotel['HotelCode']]['MinPrice'];
                                    $originalHotel['Currency'] = $priceMap[$originalHotel['HotelCode']]['Currency'];
                                }
                            }
                            unset($originalHotel);
                            // $hotels remains with all items, just some have prices.
                        } else {
                            // STRICT FILTER: Only show what is available.
                            $hotels = $availableHotels;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Date parsing error in getAllHotels: '.$e->getMessage());
                }
            }
            // -------------------------------------------------------------------------

            return view('Web.hotels', [
                'hotels' => $hotels,
                'allHotelsJson' => json_encode($hotels),
                'remainingCityCodes' => array_values($remainingCityCodes), // Pass remaining codes to view
                'cities' => [],
                'countries' => [],
                'selectedCountryCode' => 'ALL',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch all hotels: '.$e->getMessage());

            $countries = \App\Models\Country::all();
            $cities = City::whereNotNull('code')->where('code', '!=', '')->orderBy('name', 'asc')->get();

            return view('Web.hotels', [
                'hotels' => [],
                'allHotelsJson' => '[]',
                'remainingCityCodes' => [],
                'cities' => $cities,
                'countries' => $countries,
                'selectedCountryCode' => 'SA',
            ]);
        }
    }

    public function loadMoreHotels(Request $request)
    {
        try {
            $cityCodes = $request->input('cityCodes', []);
            if (empty($cityCodes) || ! is_array($cityCodes)) {
                return response()->json(['hotels' => []]);
            }

            $language = app()->getLocale();
            $apiLang = $language === 'ar' ? 'ar' : 'en';

            // Cache key for this specific batch of cities
            $cacheKey = 'hotels_batch_'.$language.'_'.md5(json_encode($cityCodes));

            $hotels = Cache::remember($cacheKey, 86400, function () use ($cityCodes, $apiLang) {
                $response = $this->hotelApi->getHotelsFromMultipleCities(
                    $cityCodes,
                    true,
                    20,
                    $apiLang,
                    1 // Max 1 page for speed
                );

                $result = $response['Hotels'] ?? [];
                if (! is_array($result)) {
                    $result = json_decode(json_encode($result), true);
                }

                return $result;
            });

            // Translation
            if ($language === 'ar' && ! empty($hotels)) {
                $translator = new \App\Services\HotelTranslationService;
                $hotels = $translator->translateHotels($hotels);
            }

            // Commission
            foreach ($hotels as &$hotel) {
                if (isset($hotel['MinPrice'])) {
                    $hotel['MinPrice'] = \App\Helpers\CommissionHelper::applyCommission((float) $hotel['MinPrice']);
                } elseif (isset($hotel['StartPrice'])) {
                    $hotel['StartPrice'] = \App\Helpers\CommissionHelper::applyCommission((float) $hotel['StartPrice']);
                }
            }
            unset($hotel);

            return response()->json(['hotels' => $hotels]);

        } catch (\Exception $e) {
            Log::error('Failed to load more hotels: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBatchMinPrices(Request $request)
    {
        try {
            $hotelIds = $request->input('hotel_ids');
            if (empty($hotelIds) || ! is_array($hotelIds)) {
                return response()->json([]);
            }

            $checkIn = $request->input('check_in', $request->input('CheckIn'));
            $checkOut = $request->input('check_out', $request->input('CheckOut'));
            $paxRooms = $request->input('pax_rooms', $request->input('PaxRooms'));

            // Default dates if not provided
            if (! $checkIn || ! $checkOut) {
                $checkIn = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                $checkOut = \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d');
            }

            // Default pax rooms if not provided
            if (empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => 2,
                        'Children' => 0,
                        'ChildrenAges' => [],
                    ],
                ];
            }

            // Use AvailabilityService (single source of truth)
            $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);
            
            $availabilityResults = $availabilityService->checkBatchAvailability(
                $hotelIds,
                $checkIn,
                $checkOut,
                $paxRooms,
                $request->input('GuestNationality', 'SA'),
                false // Lightweight mode (Standard Search)
            );

            // Convert to response format
            $results = [];
            foreach ($availabilityResults as $hotelId => $result) {
                $results[$hotelId] = $result->toArray();
            }

            return response()->json($results);

        } catch (\Exception $e) {
            Log::error('Failed to fetch batch min prices: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        set_time_limit(180);
        try {
            $request = request();
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';

            // 1. Get static hotel details (always needed for description, images, etc.)
            // We use a cache key that depends on the ID and language
            $hotelDetails = Cache::remember("hotel_details_{$id}_{$language}", 86400, function () use ($id, $language) {
                return $this->hotelApi->getHotelDetails($id, $language);
            });

            // Translate Hotel Details if Arabic
            if ($language === 'ar' && ! empty($hotelDetails['HotelDetails'])) {
                try {
                    $translator = new \App\Services\HotelTranslationService;
                    // Translate main fields (Name, Address)
                    $hotelDetails['HotelDetails'] = $translator->translateHotels($hotelDetails['HotelDetails']);

                    // Translate Facilities (Specific to Details page)
                    if (isset($hotelDetails['HotelDetails'][0]['HotelFacilities']) && is_array($hotelDetails['HotelDetails'][0]['HotelFacilities'])) {
                        $facilities = $hotelDetails['HotelDetails'][0]['HotelFacilities'];
                        // Translate the list of facilities efficiently (batch)
                        $translatedFacilities = $translator->translateStrings($facilities, 'en', 'ar');

                        // Map back to the facilities array
                        $hotelDetails['HotelDetails'][0]['HotelFacilities'] = array_values(array_map(function ($facility) use ($translatedFacilities) {
                            return $translatedFacilities[$facility] ?? $facility;
                        }, $facilities));
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to translate hotel details: '.$e->getMessage());
                }
            }

            // 2. Check for availability (handle capitalized or lowercase params)
            $availableRooms = [];
            $checkIn = $request->input('CheckIn', $request->input('check_in'));
            $checkOut = $request->input('CheckOut', $request->input('check_out'));

            $paxRooms = $request->input('PaxRooms');

            // Backward Compatibility / Fallback
            if (empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => (int) $request->input('guests', $request->input('adults', 2)),
                        'Children' => (int) $request->input('children', 0),
                        'ChildrenAges' => [],
                    ],
                ];
            }

            // Ensure correct structure for API with strict validation (same as getCityHotels)
            if (is_array($paxRooms)) {
                $cleanedPaxRooms = [];
                foreach ($paxRooms as $room) {
                    $adults = filter_var($room['Adults'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
                    $children = filter_var($room['Children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);

                    $childrenAges = $room['ChildrenAges'] ?? [];
                    if (! is_array($childrenAges)) {
                        $childrenAges = [];
                    }

                    // Cast ages to integers and clamp to 0-12 (matching client requirements)
                    $childrenAges = array_map(function ($age) {
                        $ageInt = (int) $age;

                        return max(0, min(12, $ageInt));
                    }, $childrenAges);

                    if ($children > count($childrenAges)) {
                        for ($i = count($childrenAges); $i < $children; $i++) {
                            $childrenAges[] = 0;
                        }
                    } elseif ($children < count($childrenAges)) {
                        $childrenAges = array_slice($childrenAges, 0, $children);
                    }

                    $cleanedPaxRooms[] = [
                        'Adults' => $adults,
                        'Children' => $children,
                        'ChildrenAges' => $childrenAges,
                    ];
                }
                $paxRooms = $cleanedPaxRooms;
            }

            // Set default dates if missing (Unified with listing page)
            if (empty($checkIn) || empty($checkOut)) {
                $checkIn = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                $checkOut = \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d');
            }

            // Calculate total guests for display
            $guestsCount = 0;
            foreach ($paxRooms as $room) {
                $guestsCount += ($room['Adults'] ?? 1) + ($room['Children'] ?? 0);
            }


            if ($checkIn && $checkOut) {
                try {
                    // Use AvailabilityService (single source of truth)
                    $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);
                    
                    $availability = $availabilityService->checkAvailability(
                        $id,
                        $checkIn,
                        $checkOut,
                        $paxRooms,
                        $request->input('GuestNationality', 'SA')
                    );
                    
                    // Extract available rooms
                    $availableRooms = $availability->rooms;
                    $currency = $availability->currency;
                    
                    Log::info("Hotel details - Availability check complete", [
                        'hotel_id' => $id,
                        'status' => $availability->status,
                        'rooms_count' => count($availableRooms),
                        'min_price' => $availability->minPrice
                    ]);

                } catch (\Exception $e) {
                    Log::error('Error fetching room availability: '.$e->getMessage());
                }
            }

            // Translate Room Names if Arabic and rooms exist
            if ($language === 'ar' && ! empty($availableRooms)) {
                try {
                    $translator = new \App\Services\HotelTranslationService;
                    // Extract unique room names to translate
                    $roomNames = [];
                    foreach ($availableRooms as $room) {
                        $name = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                        if (! empty($name)) {
                            $roomNames[] = $name;
                        }
                    }
                    $roomNames = array_unique($roomNames);

                    if (! empty($roomNames)) {
                        // Use persistent cache translation
                        $translatedNames = $translator->translateRoomNames($roomNames);

                        // Apply translations back to rooms
                        foreach ($availableRooms as &$room) {
                            $originalName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                            if (isset($translatedNames[$originalName])) {
                                if (is_array($room['Name'])) {
                                    $room['Name'][0] = $translatedNames[$originalName];
                                } else {
                                    $room['Name'] = $translatedNames[$originalName];
                                }
                            }
                        }
                        unset($room);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to translate room names: '.$e->getMessage());
                }
            }



            // Log response for debugging
            // Log::info('Hotel details response', [
            //     'hotel_id' => $id,
            //     'status_code' => $hotelDetails['Status']['Code'] ?? 'unknown',
            // ]);

            return view('Web.hotel-details', [
                'hotelId' => $id,
                'hotelDetails' => $hotelDetails,
                'availableRooms' => $availableRooms, // Pass real rooms
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'guests' => $guestsCount,
                'paxRooms' => $paxRooms,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch hotel details: '.$e->getMessage());

            // Return view with error message
            return view('Web.hotel-details', [
                'hotelId' => $id,
                'hotelDetails' => null,
                'availableRooms' => [],
                'error' => __('Failed to load hotel details'),
            ]);
        }
    }

    public function reservation(Request $request)
    {
        set_time_limit(180);
        try {
            $hotelId = $request->input('hotel_id') ?? session('_old_input.hotel_id');
            $bookingCode = $request->input('booking_code') ?? session('_old_input.booking_code');
            $checkIn = $request->input('CheckIn', $request->input('check_in')) ?? session('_old_input.check_in');
            $checkOut = $request->input('CheckOut', $request->input('check_out')) ?? session('_old_input.check_out');

            $paxRooms = $request->input('PaxRooms');
            if (empty($paxRooms)) {
                $guests = $request->input('guests', session('_old_input.guests', 2));
                $paxRooms = [[
                    'Adults' => (int) $guests,
                    'Children' => 0,
                    'ChildrenAges' => [],
                ]];
            }

            // Sync PaxRooms structure with unified logic
            if (is_array($paxRooms)) {
                $cleanedPaxRooms = [];
                foreach ($paxRooms as $room) {
                    $adults = filter_var($room['Adults'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
                    $children = filter_var($room['Children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);
                    $childrenAges = $room['ChildrenAges'] ?? [];
                    if (!is_array($childrenAges)) $childrenAges = [];
                    $childrenAges = array_map(fn($age) => max(0, min(12, (int)$age)), $childrenAges);
                    if ($children > count($childrenAges)) {
                        for ($i = count($childrenAges); $i < $children; $i++) $childrenAges[] = 0;
                    } elseif ($children < count($childrenAges)) {
                        $childrenAges = array_slice($childrenAges, 0, $children);
                    }
                    $cleanedPaxRooms[] = ['Adults' => $adults, 'Children' => $children, 'ChildrenAges' => $childrenAges];
                }
                $paxRooms = $cleanedPaxRooms;
            }

            // Sync guests count for display
            $guests = 0;
            foreach ($paxRooms as $room) {
                $guests += ($room['Adults'] ?? 1) + ($room['Children'] ?? 0);
            }

            // More robust validation - check for null, empty string, or missing values
            $hasHotelId = ! empty($hotelId) && $hotelId !== '';
            $hasCheckIn = ! empty($checkIn) && $checkIn !== '';
            $hasCheckOut = ! empty($checkOut) && $checkOut !== '';
            $hasBookingCode = ! empty($bookingCode) && $bookingCode !== '';

            // Log the validation for debugging
            Log::info('Reservation validation', [
                'hotel_id' => $hotelId,
                'booking_code' => $bookingCode,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'has_hotel_id' => $hasHotelId,
                'has_check_in' => $hasCheckIn,
                'has_check_out' => $hasCheckOut,
                'has_booking_code' => $hasBookingCode,
            ]);

            // Only redirect if critical data is missing
            if (! $hasHotelId || ! $hasCheckIn || ! $hasCheckOut) {
                Log::warning('Reservation validation failed - missing required data', [
                    'hotel_id' => $hotelId,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]);

                // If hotelId is still missing, we can't show details, go to home or all hotels
                if (! $hasHotelId) {
                    return redirect()->route('all.hotels', ['locale' => app()->getLocale()])
                        ->with('error', __('Please select a hotel first'));
                }

                return redirect()->route('hotel.details', ['locale' => app()->getLocale(), 'id' => $hotelId])
                    ->with('error', __('Please select dates and room'));
            }

            // Get hotel details
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $hotelDetails = null;
            $roomData = null;

            try {
                $hotelDetails = $this->hotelApi->getHotelDetails($hotelId, $language);
            } catch (\Exception $e) {
                Log::error('Failed to fetch hotel details for reservation: '.$e->getMessage());
            }

            // Search for room details using AvailabilityService (Single Source of Truth)
            if ($bookingCode && $checkIn && $checkOut) {
                try {
                    $availabilityService = app(\App\Services\Hotel\AvailabilityService::class);
                    $availability = $availabilityService->checkAvailability(
                        $hotelId,
                        $checkIn,
                        $checkOut,
                        $paxRooms,
                        $request->input('GuestNationality', 'SA')
                    );

                    if ($availability->isAvailable()) {
                        foreach ($availability->rooms as $room) {
                            if (isset($room['BookingCode']) && $room['BookingCode'] === $bookingCode) {
                                $roomData = $room;
                                $roomData['Currency'] = $availability->currency;
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch room details using AvailabilityService: '.$e->getMessage());
                }
            }

            // If room data not found from API, use URL parameters or session as fallback
            if (! $roomData) {
                // The total_fare from the request already includes commission.
                $commissionedFare = (float) ($request->input('total_fare') ?? session('_old_input.total_fare', 0));

                $roomData = [
                    'BookingCode' => $bookingCode,
                    'Name' => [$request->input('room_name') ?? session('_old_input.room_name', 'Room')],
                    'TotalFare' => $commissionedFare,
                    'Currency' => $request->input('currency') ?? session('_old_input.currency', 'USD'),
                    'Inclusion' => $request->input('inclusion') ?? session('_old_input.inclusion', ''),
                    'TotalTax' => 0,
                ];
            }

            // Calculate nights
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $nights = $checkInDate->diffInDays($checkOutDate);

            // Get payment data for PayFort
            $totalFare = 0;
            $currency = 'USD';
            $customerEmail = auth()->check() ? auth()->user()->email : '';

            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                $totalFare = (float) $roomData['TotalFare'];
                $currency = $roomData['Currency'] ?? 'USD';
            } elseif ($request->input('total_fare')) {
                $totalFare = (float) $request->input('total_fare');
                $currency = $request->input('currency', 'USD');
            }

            // totalFare is already commissioned (either in loop or from request)

            // Do not generate payment data immediately.
            // We want the user to go through the review process (either AJAX or separate page)
            // where the actual Booking record is created.
            $paymentData = null;

            // If request is AJAX and wants JSON response (for payment data)
            // DEPRECATED: Booking creation now happens in review() method
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please use the review step to initiate booking.',
                ], 400);
            }

            return view('Web.reservation', [
                'hotelId' => $hotelId,
                'hotelDetails' => $hotelDetails,
                'roomData' => $roomData,
                'bookingCode' => $bookingCode,
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'guests' => $guests,
                'nights' => $nights,
                'paymentData' => $paymentData,
                'totalFare' => $totalFare,
                'currency' => $currency,
                'paxRooms' => $paxRooms,
            ]);
        } catch (\Exception $e) {
            Log::error('Reservation page error: '.$e->getMessage());

            return redirect()->route('hotel.details', ['locale' => app()->getLocale(), 'id' => $request->input('hotel_id', 1)])
                ->with('error', __('Failed to load reservation page'));
        }
    }

    public function review(ReservationReviewRequest $request)
    {
        set_time_limit(180);
        try {
            $hotelId = $request->input('hotel_id');
            $bookingCode = $request->input('booking_code');
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');
            $guests = $request->input('guests', 1);
            $paxRooms = $request->input('PaxRooms');

            if (empty($paxRooms)) {
                $paxRooms = [[
                    'Adults' => (int) $guests,
                    'Children' => 0,
                    'ChildrenAges' => [],
                ]];
            }

            // Sync guests count for display if needed
            $guests = 0;
            foreach ($paxRooms as $room) {
                $guests += ($room['Adults'] ?? 1) + ($room['Children'] ?? 0);
            }

            // Get guest information
            $guestName = $request->input('name');
            $guestEmail = $request->input('email');
            $guestPhone = $request->input('phone');
            $guestPhoneCountryCode = $request->input('phone_country_code');
            $guestNotes = $request->input('notes', '');

            // If user is authenticated, use their data if form data is not provided
            if (auth()->check()) {
                $user = auth()->user();
                $guestName = $guestName ?: $user->name;
                $guestEmail = $guestEmail ?: $user->email;
                $guestPhone = $guestPhone ?: ($user->phone ?? '');
                $guestPhoneCountryCode = $guestPhoneCountryCode ?: ($user->phone_country_code ?? '');
            }

            // Get hotel details
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $hotelDetails = null;
            $roomData = null;

            try {
                $hotelDetails = $this->hotelApi->getHotelDetails($hotelId, $language);
            } catch (\Exception $e) {
                Log::error('Failed to fetch hotel details for review: '.$e->getMessage());
            }

            // Fetch alternate language details for localized content
            $altLanguage = $language === 'ar' ? 'en' : 'ar';
            $hotelDetailsAlt = null;
            try {
                $hotelDetailsAlt = \Illuminate\Support\Facades\Cache::remember("hotel_details_{$hotelId}_{$altLanguage}", 86400, function () use ($hotelId, $altLanguage) {
                    return $this->hotelApi->getHotelDetails($hotelId, $altLanguage);
                });
            } catch (\Exception $e) {
                Log::warning('Failed to fetch alternate hotel details: '.$e->getMessage());
            }

            // Search for room details using booking_code
            if ($bookingCode && $checkIn && $checkOut) {
                try {
                    $searchData = [
                        'CheckIn' => $checkIn,
                        'CheckOut' => $checkOut,
                        'HotelCodes' => $hotelId,
                        'GuestNationality' => 'SA',
                        'PaxRooms' => $paxRooms,
                        'ResponseTime' => 18,
                        'IsDetailedResponse' => true,
                        'Filters' => [
                            'Refundable' => true,
                            'NoOfRooms' => count($paxRooms),
                            'MealType' => 'All',
                        ],
                    ];

                    $searchResponse = $this->hotelApi->searchHotel($searchData);

                    // Find the room with matching booking_code or fallback to matching by name
                    if (isset($searchResponse['HotelResult']) && is_array($searchResponse['HotelResult'])) {
                        $foundRoom = null;
                        $requestedRoomName = $request->input('room_name');

                        foreach ($searchResponse['HotelResult'] as $hotel) {
                            if (isset($hotel['Rooms']) && is_array($hotel['Rooms'])) {
                                foreach ($hotel['Rooms'] as $room) {
                                    // Primary match: BookingCode
                                    if (isset($room['BookingCode']) && $room['BookingCode'] === $bookingCode) {
                                        $foundRoom = $room;
                                        break 2;
                                    }

                                    // Secondary match: Room Name + Inclusion (Better for same names)
                                    if (! $foundRoom) {
                                        $currentRoomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                        $requestedInclusion = $request->input('inclusion');
                                        $currentInclusion = $room['Inclusion'] ?? '';

                                        if ($requestedRoomName && $currentRoomName === $requestedRoomName) {
                                            // If inclusion matches, it's definitely the one
                                            if ($requestedInclusion && $currentInclusion === $requestedInclusion) {
                                                $foundRoom = $room;
                                            } else {
                                                // Otherwise, hold it as a potential match
                                                $foundRoom = $room;
                                            }
                                        }
                                    }

                                }
                            }
                        }

                        if ($foundRoom) {
                            // CRITICAL: Apply commission to roomData ONLY ONCE after the search loop finishes
                            // We use PublishedPrice as the base if available, otherwise TotalFare
                            $basePrice = (float) ($foundRoom['Price']['PublishedPrice'] ?? $foundRoom['TotalFare'] ?? 0);

                            $roomData = $foundRoom;
                            $roomData['TotalFare'] = \App\Helpers\CommissionHelper::applyCommission($basePrice);
                            $roomData['Currency'] = $searchResponse['HotelResult'][0]['Currency'] ?? 'USD';

                            // Final safety check: ensure no local confirmed booking exists for this room/dates
                            $roomName = is_array($roomData['Name']) ? ($roomData['Name'][0] ?? '') : ($roomData['Name'] ?? '');
                            $isLocallyBooked = \App\Models\HotelBooking::where('hotel_code', $hotelId)
                                ->where('booking_status', \App\Constants\BookingStatus::CONFIRMED)
                                ->where('room_name', $roomName)
                                ->where(function ($query) use ($checkIn, $checkOut) {
                                    $query->where('check_in', '<', $checkOut)
                                        ->where('check_out', '>', $checkIn);
                                })
                                ->exists();

                            if ($isLocallyBooked) {
                                throw new \Exception(__('This room is no longer available for the selected dates.'));
                            }

                            // Update bookingCode to the potentially fresh one
                            $bookingCode = $roomData['BookingCode'];
                        }
                    }

                    // Perform PreBook to "lock" the price and verify availability
                    if ($roomData && isset($roomData['BookingCode'])) {
                        $preBookResponse = $this->hotelApi->preBook($roomData['BookingCode']);
                        if (isset($preBookResponse['Status']['Code']) && $preBookResponse['Status']['Code'] == 200) {
                            // Update roomData, bookingCode and totalFare from PreBook response if refreshed
                            if (isset($preBookResponse['BookingCode'])) {
                                $bookingCode = $preBookResponse['BookingCode'];
                                $roomData['BookingCode'] = $bookingCode;
                            }

                            // TBO returns the BASE price in PreBook. We apply commission to it.
                            if (isset($preBookResponse['HotelResult'][0]['Rooms'][0]['TotalFare'])) {
                                $basePrice = (float) $preBookResponse['HotelResult'][0]['Rooms'][0]['TotalFare'];
                                $roomData['TotalFare'] = \App\Helpers\CommissionHelper::applyCommission($basePrice);
                                $roomData['Currency'] = $preBookResponse['HotelResult'][0]['Currency'] ?? ($roomData['Currency'] ?? 'USD');

                                if (isset($roomData['Price'])) {
                                    $roomData['Price']['PublishedPrice'] = $roomData['TotalFare'];
                                    $roomData['Price']['Amount'] = $roomData['TotalFare'];
                                }
                            } elseif (isset($preBookResponse['Price']['PublishedPrice'])) {
                                $basePrice = (float) $preBookResponse['Price']['PublishedPrice'];
                                $roomData['TotalFare'] = \App\Helpers\CommissionHelper::applyCommission($basePrice);
                                $roomData['Currency'] = $preBookResponse['Price']['Currency'] ?? ($roomData['Currency'] ?? 'USD');

                                if (isset($roomData['Price'])) {
                                    $roomData['Price']['PublishedPrice'] = $roomData['TotalFare'];
                                }
                            }

                            Log::info('PreBook successful in review step', [
                                'booking_code' => $bookingCode,
                                'commissioned_price' => $roomData['TotalFare'] ?? 'unchanged',
                            ]);
                        } else {
                            $errorMsg = $preBookResponse['Status']['Description'] ?? 'Room no longer available';
                            Log::warning('PreBook failed in review step: '.$errorMsg);
                            throw new \Exception(__('The selected room is no longer available: ').$errorMsg);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch room details for review: '.$e->getMessage());
                }
            }

            // If room data not found from API, use URL parameters as fallback
            if (! $roomData) {
                $roomData = [
                    'BookingCode' => $bookingCode,
                    'Name' => [$request->input('room_name', 'Room')],
                    'TotalFare' => (float) $request->input('total_fare', 0),
                    'Currency' => $request->input('currency', 'USD'),
                    'Inclusion' => $request->input('inclusion', ''),
                    'TotalTax' => 0,
                ];
            }

            // Calculate nights
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $nights = $checkInDate->diffInDays($checkOutDate);

            // Get payment data for PayFort
            $totalFare = 0;
            $currency = 'USD';

            // Extract price from room data
            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                $totalFare = (float) $roomData['TotalFare'];
                $currency = $roomData['Currency'] ?? 'USD';
            }

            // Fallback to request input if totalFare is still 0
            if ($totalFare == 0 && $request->input('total_fare')) {
                // If we are taking total_fare from the request, it ALREADY includes commission.
                $totalFare = (float) $request->input('total_fare');
                $currency = $request->input('currency', 'USD');
            }

            // Determine localized names
            $currentName = $hotelDetails['HotelDetails'][0]['HotelName'] ?? $hotelDetails['Name'] ?? 'Unknown Hotel';
            $altName = $hotelDetailsAlt['HotelDetails'][0]['HotelName'] ?? $hotelDetailsAlt['Name'] ?? null;

            $hotelNameAr = $language === 'ar' ? $currentName : $altName;
            $hotelNameEn = $language === 'en' ? $currentName : $altName;

            // totalFare is already commissioned at this point

            // Handle Discount Code
            $originalPrice = $totalFare;
            $discountAmount = 0;
            $discountCodeId = null;
            $discountCodeInput = $request->input('discount_code');
            $discountError = null;

            if ($discountCodeInput) {
                $codeModel = \App\Models\DiscountCode::where('code', $discountCodeInput)->first();
                if ($codeModel) {
                    if ($codeModel->isValid()) {
                        $discountAmount = $totalFare * ($codeModel->discount_percentage / 100);
                        $totalFare = $totalFare - $discountAmount;
                        $discountCodeId = $codeModel->id;
                    } else {
                        if ($codeModel->is_used) {
                            $discountError = __('This discount code has already been used.');
                        } else {
                            $discountError = __('This discount code has expired or is not yet active.');
                        }
                    }
                } else {
                    $discountError = __('Invalid discount code.');
                }
            }

            // Prepare data for booking creation
            $bookingData = [
                'hotel_code' => $hotelId,
                'hotel_name' => $currentName,
                'hotel_name_ar' => $hotelNameAr,
                'hotel_name_en' => $hotelNameEn,
                'room_code' => $bookingCode,
                'room_name' => is_array($roomData['Name']) ? ($roomData['Name'][0] ?? 'Room') : ($roomData['Name'] ?? 'Room'),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $totalFare,
                'original_price' => $originalPrice,
                'discount_amount' => $discountAmount,
                'discount_code_id' => $discountCodeId,
                'currency' => $currency,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_phone' => $guestPhone,
                'phone_country_code' => $guestPhoneCountryCode,
                'booking_status' => \App\Constants\BookingStatus::PENDING,
                'payment_status' => \App\Constants\PaymentStatus::PENDING,
            ];

            // Create booking record
            $booking = $this->bookingService->initiateBooking($bookingData);

            // Generate payment data using the REAL booking reference
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $totalFare,
                'currency' => $currency,
                'customer_email' => $guestEmail,
                'merchant_reference' => $booking->booking_reference,
            ]);

            return view('Web.reservation-review', [
                'hotelId' => $hotelId,
                'hotelDetails' => $hotelDetails,
                'roomData' => $roomData,
                'bookingCode' => $bookingCode,
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'guests' => $guests,
                'nights' => $nights,
                'paymentData' => $paymentData,
                'totalFare' => $totalFare,
                'originalPrice' => $originalPrice,
                'discountAmount' => $discountAmount,
                'discountError' => $discountError,
                'discountCode' => $discountCodeInput,
                'currency' => $currency,
                'guestName' => $guestName,
                'guestEmail' => $guestEmail,
                'guestPhone' => $guestPhone,
                'phone_country_code' => $guestPhoneCountryCode,
                'guestNotes' => $guestNotes,
            ]);
        } catch (\Exception $e) {
            Log::error('Review page error: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('reservation', ['locale' => app()->getLocale()])
                ->withInput()
                ->with('error', __('Failed to load review page').': '.$e->getMessage());
        }
    }

    /**
     * Helper method to translate countries to Arabic manually
     */
    private function translateCountries(array $countries): array
    {
        $arabicMap = [
            'SA' => 'المملكة العربية السعودية',
            'AE' => 'الإمارات العربية المتحدة',
            'EG' => 'مصر',
            'BH' => 'البحرين',
            'KW' => 'الكويت',
            'QA' => 'قطر',
            'OM' => 'عمان',
            'JO' => 'الأردن',
            'TR' => 'تركيا',
            'GB' => 'المملكة المتحدة',
            'US' => 'الولايات المتحدة',
            'FR' => 'فرنسا',
            'DE' => 'ألمانيا',
            'IT' => 'إيطاليا',
            'ES' => 'إسبانيا',
            'CH' => 'سويسرا',
            'MY' => 'ماليزيا',
            'ID' => 'إندونيسيا',
            'TH' => 'تايلاند',
            'MA' => 'المغرب',
            'LB' => 'لبنان',
            'DZ' => 'الجزائر',
            'TN' => 'تونس',
            'IQ' => 'العراق',
            'SD' => 'السودان',
            'YE' => 'اليمن',
            'SY' => 'سوريا',
            'PS' => 'فلسطين',
            'AT' => 'النمسا',
            'GR' => 'اليونان',
            'RU' => 'روسيا',
            'CN' => 'الصين',
            'JP' => 'اليابان',
            'KR' => 'كوريا الجنوبية',
            'IN' => 'الهند',
            'PK' => 'باكستان',
            'AU' => 'أستراليا',
            'CA' => 'كندا',
            'BR' => 'البرازيل',
            'AR' => 'الأرجنتين',
            'ZA' => 'جنوب أفريقيا',
            'NL' => 'هولندا',
            'BE' => 'بلجيكا',
            'SE' => 'السويد',
            'NO' => 'النرويج',
            'DK' => 'الدانمارك',
            'PT' => 'البرتغال',
            'IE' => 'أيرلندا',
            'MV' => 'المالديف',
            'MU' => 'موريشيوس',
            'PH' => 'الفلبين',
            'VN' => 'فيتنام',
            'SG' => 'سنغافورة',
            'LK' => 'سريلانكا',
            'NP' => 'نيبال',
            'BD' => 'بنجلاديش',
            'AF' => 'أفغانستان',
            'IR' => 'إيران',
            'AZ' => 'أذربيجان',
            'GE' => 'جورجيا',
            'AM' => 'أرمينيا',
            'KZ' => 'كازاخستان',
            'UZ' => 'أوزبكستان',
            'TM' => 'تركمانستان',
            'KG' => 'قيرغيزستان',
            'TJ' => 'طاجيكستان',
            'UA' => 'أوكرانيا',
            'BY' => 'بيلاروسيا',
            'PL' => 'بولندا',
            'CZ' => 'التشيك',
            'SK' => 'سلوفاكيا',
            'HU' => 'المجر',
            'RO' => 'رومانيا',
            'BG' => 'بلغاريا',
            'RS' => 'صربيا',
            'HR' => 'كرواتيا',
            'SI' => 'سلوفينيا',
            'BA' => 'البوسنة والهرسك',
            'ME' => 'الجبل الأسود',
            'MK' => 'مقيدونيا الشمالية',
            'AL' => 'ألبانيا',
            'CY' => 'قبرص',
            'MT' => 'مالطا',
            'IS' => 'أيسلندا',
            'FI' => 'فنلندا',
            'EE' => 'إستونيا',
            'LV' => 'اتفيا',
            'LT' => 'ليتوانيا',
            'LU' => 'لوكسمبورغ',
            'MC' => 'موناكو',
            'LI' => 'ليختنشتاين',
            'SM' => 'سان مارينو',
            'VA' => 'الفاتيكان',
            'AD' => 'أندورا',
            'MX' => 'المكسيك',
            'CO' => 'كولومبيا',
            'PE' => 'بيرو',
            'CL' => 'تشيلي',
            'VE' => 'فنزويلا',
            'EC' => 'الإكوادور',
            'BO' => 'بوليفيا',
            'UY' => 'أوروغواي',
            'PY' => 'باراغواي',
            'NZ' => 'نيوزيلندا',
            'FJ' => 'فيجي',
            'PG' => 'بابوا غينيا الجديدة',
            'NG' => 'نيجيريا',
            'ET' => 'إثيوبيا',
            'KE' => 'كينيا',
            'TZ' => 'تنزانيا',
            'UG' => 'أوغندا',
            'GH' => 'غانا',
            'CI' => 'ساحل العاج',
            'SN' => 'السنغال',
            'CM' => 'الكاميرون',
            'AO' => 'أنغولا',
            'ZM' => 'زامبيا',
            'ZW' => 'زيمبابوي',
            'BW' => 'بوتسوانا',
            'NA' => 'ناميبيا',
            'MZ' => 'موزمبيق',
            'MG' => 'مدغشقر',
            'SC' => 'سيشل',
            'SO' => 'الصومال',
            'DJ' => 'جيبوتي',
            'ER' => 'إريتريا',
            'LY' => 'ليبيا',
            'MR' => 'موريتانيا',
            'SL' => 'سيراليون',
            'LR' => 'ليبيريا',
            'GN' => 'غينيا',
            'GM' => 'غامبيا',
            'GW' => 'غينيا بيساو',
            'CV' => 'الرأس الأخضر',
            'ST' => 'ساو تومي وبرينسيبي',
            'GQ' => 'غينيا الاستوائية',
            'GA' => 'الغابون',
            'CG' => 'الكونغو',
            'CD' => 'الكونغو الديمقراطية',
            'CF' => 'أفريقيا الوسطى',
            'TD' => 'تشاد',
            'NE' => 'النيجر',
            'ML' => 'مالي',
            'BF' => 'بوركينا فاسو',
            'BJ' => 'بنين',
            'TG' => 'توغو',
            'RW' => 'رواندا',
            'BI' => 'بوروندي',
            'SS' => 'جنوب السودان',
            'LS' => 'ليسوتو',
            'SZ' => 'إسواتيني',
            'KM' => 'جزر القمر',
            'RE' => 'ريونيون',
            'YT' => 'مايوت',
            'SH' => 'سانت هيلانة',
            'CU' => 'كوبا',
            'JM' => 'جامايكا',
            'HT' => 'هايتي',
            'DO' => 'الدومينيكان',
            'BS' => 'جزر البهاما',
            'BB' => 'بربادوس',
            'TT' => 'ترينيداد وتوباغو',
            'CR' => 'كوستاريكا',
            'PA' => 'بنما',
            'BZ' => 'بيليز',
            'GT' => 'غواتيمالا',
            'HN' => 'هندوراس',
            'SV' => 'السلفادور',
            'NI' => 'نيكاراغوا',
            'GL' => 'جرينلاند',
            'FO' => 'جزر فارو',
            'SJ' => 'سفالبارد',
            'GI' => 'جبل طارق',
            'RS' => 'صربيا',
        ];

        // Filter out invalid/test countries and translate
        $filteredCountries = [];
        foreach ($countries as $country) {
            $code = $country['Code'] ?? $country['CountryCode'] ?? '';
            $name = $country['Name'] ?? $country['CountryName'] ?? '';

            // Skip invalid/test entries
            $invalidPatterns = [
                'NotAvailable', 'Dummy', 'Buffer', '-1', 'Test',
                'European Monetary Union', 'Netherlands Antilles',
            ];

            $isInvalid = false;
            foreach ($invalidPatterns as $pattern) {
                if (stripos($name, $pattern) !== false || stripos($code, $pattern) !== false) {
                    $isInvalid = true;
                    break;
                }
            }

            if ($isInvalid) {
                continue; // Skip this country
            }

            // Translate if available
            if (isset($arabicMap[$code])) {
                $country['Name'] = $arabicMap[$code];
                $country['CountryName'] = $arabicMap[$code];
            } elseif (isset($arabicMap[$name])) {
                $country['Name'] = $arabicMap[$name];
                $country['CountryName'] = $arabicMap[$name];
            }

            $filteredCountries[] = $country;
        }

        return $filteredCountries;
    }

    /**
     * Helper method to translate cities to Arabic using CityTranslationService
     */
    private function translateCities(array $cities): array
    {
        // Extract names to translate
        $names = [];
        foreach ($cities as $city) {
            $name = $city['CityName'] ?? $city['Name'] ?? '';
            if (! empty($name)) {
                $names[] = trim($name);
            }
        }

        // Use the service to batch translate
        // This handles: Check Local DB -> Google Translate if missing -> Save Local DB
        $translationService = new \App\Services\CityTranslationService;
        $translatedNames = $translationService->translateBatch($names, 5); // Limit 5 API calls per request

        foreach ($cities as &$city) {
            $name = $city['CityName'] ?? $city['Name'] ?? '';
            if (empty($name)) {
                continue;
            }

            $name = trim($name);

            // Manual Overrides for specific tricky cases
            if (stripos($name, 'Al Lith') !== false) {
                $city['CityName'] = 'الليث';
                $city['Name'] = 'الليث';

                continue;
            }
            if (stripos($name, 'Al Qunfudhah') !== false) {
                $city['CityName'] = 'القنفذة';
                $city['Name'] = 'القنفذة';

                continue;
            }

            // Apply Translation from Service
            if (isset($translatedNames[$name])) {
                $city['CityName'] = $translatedNames[$name];
                $city['Name'] = $translatedNames[$name];
            }
        }

        return $cities;
    }

    public function bookingSuccess($reference)
    {
        $booking = \App\Models\HotelBooking::where('booking_reference', $reference)->firstOrFail();

        return view('Web.booking-success', compact('booking'));
    }
}
