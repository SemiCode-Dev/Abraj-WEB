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
                'GuestNationality' => $request->input('GuestNationality', 'AE'),
                'PaxRooms' => $paxRooms,
                'ResponseTime' => $request->input('ResponseTime', 18),
                'IsDetailedResponse' => $request->input('IsDetailedResponse', true),
                'Filters' => $request->input('Filters', [
                    'Refundable' => true,
                    'NoOfRooms' => 0,
                    'MealType' => 'All',
                ]),
            ];

            // Log request data for debugging
            Log::info('Hotel search request', $data);

            // Create cache key based on search parameters
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $data['Language'] = $language;
            $cacheKey = 'hotel_search_'.md5(json_encode($data));

            // Cache search results for 5 minutes (300 seconds) - reduced from 1 hour
            // Shorter cache ensures fresher availability data after bookings
            $response = Cache::remember($cacheKey, 300, function () use ($data) {
                return $this->hotelApi->searchHotel($data);
            });

            // Filter rooms based on LOCAL bookings (Safety Layer)
            // 1. Filter CONFIRMED: Normal bookings
            // 2. Filter PENDING: Payment in progress
            if (isset($response['HotelResult']) && is_array($response['HotelResult'])) {
                foreach ($response['HotelResult'] as $hotelIndex => $hotel) {
                    if (isset($hotel['Rooms']) && is_array($hotel['Rooms'])) {
                        $hotelCode = $hotel['HotelCode'] ?? '';

                        // Count bookings that should reduce availability (CONFIRMED + PENDING)
                        $reservedRoomCounts = \App\Models\HotelBooking::where('hotel_code', $hotelCode)
                            ->whereIn('booking_status', [
                                \App\Constants\BookingStatus::CONFIRMED,
                                \App\Constants\BookingStatus::PENDING,
                            ])
                            ->where(function ($query) use ($checkIn, $checkOut) {
                                $query->where('check_in', '<', $checkOut)
                                    ->where('check_out', '>', $checkIn);
                            })
                            ->selectRaw('room_name, COUNT(*) as reserved_count')
                            ->groupBy('room_name')
                            ->pluck('reserved_count', 'room_name')
                            ->toArray();

                        // Only filter if there are reserved rooms
                        if (! empty($reservedRoomCounts)) {
                            // Group rooms by name to count how many TBO returned
                            $roomsByName = [];
                            foreach ($hotel['Rooms'] as $room) {
                                $roomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                if (! isset($roomsByName[$roomName])) {
                                    $roomsByName[$roomName] = [];
                                }
                                $roomsByName[$roomName][] = $room;
                            }

                            // For each room type, remove the number that are Reserved
                            $filteredRooms = [];
                            foreach ($roomsByName as $roomName => $rooms) {
                                $tboAvailableCount = count($rooms);
                                $reservedCount = $reservedRoomCounts[$roomName] ?? 0;
                                $actuallyAvailableCount = max(0, $tboAvailableCount - $reservedCount);

                                // Add only the actually available rooms
                                for ($i = 0; $i < $actuallyAvailableCount; $i++) {
                                    if (isset($rooms[$i])) {
                                        $filteredRooms[] = $rooms[$i];
                                    }
                                }

                                if ($reservedCount > 0) {
                                    Log::info('Room availability adjusted (Confirmed+Pending)', [
                                        'hotel' => $hotelCode,
                                        'room_type' => $roomName,
                                        'tbo_count' => $tboAvailableCount,
                                        'reserved_count' => $reservedCount,
                                        'showing_count' => $actuallyAvailableCount,
                                    ]);
                                }
                            }

                            $response['HotelResult'][$hotelIndex]['Rooms'] = $filteredRooms;
                        }
                    }
                }
            }

            // Log response for debugging
            Log::info('Hotel search response', [
                'status_code' => $response['Status']['Code'] ?? 'unknown',
                'hotels_count' => isset($response['Hotels']) ? count($response['Hotels']) : 0,
                'cached' => Cache::has($cacheKey),
            ]);

            // TBO API manages room inventory, but we add a local safety layer
            // to handle Stale Cache, Slow Updates, and Pending Payments
            return response()->json($response);
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
                    // We fetch multiple pages to ensure we get all potential hotels
                    // But since it's lightweight, it's much faster
                    $response = $this->hotelApi->getAllCityHotels($cityCode, false, $language);

                    $hotels = $response['Hotels'] ?? [];
                    if (! is_array($hotels)) {
                        $hotels = json_decode(json_encode($hotels), true);
                    }

                    // Update local DB with hotels count for popularity tracking (Self-Learning)
                    // We catch exceptions to prevent breaking the flow if DB update fails
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
            $checkIn = request('CheckIn');
            $checkOut = request('CheckOut');
            $adults = request('adults');
            $children = request('children');

            if ($checkIn && $checkOut && $adults) {
                Log::info('City Availability Search', ['city' => $cityCode, 'in' => $checkIn, 'out' => $checkOut, 'adults' => $adults]);

                // 1. We have $allLightweightHotels from the city list
                if (! empty($allLightweightHotels)) {
                    // 2. Extract codes (Limit to 50-100 to avoid API overflow/timeouts)
                    // TBO Search allows multiple codes, but performance degrades.
                    // Let's take the top 50 hotels (which are usually the most relevant if sorted,
                    // or just the first 50 found) to check availability.
                    // If we want more, we'd need complex background processing or pagination of SEARCH itself.
                    $candidateHotels = array_slice($allLightweightHotels, 0, 50);
                    $candidateCodes = array_column($candidateHotels, 'HotelCode');
                    $codesString = implode(',', $candidateCodes);

                    if (! empty($codesString)) {
                        try {
                            $searchData = [
                                'CheckIn' => $checkIn,
                                'CheckOut' => $checkOut,
                                'HotelCodes' => $codesString,
                                'GuestNationality' => 'AE',
                                'PaxRooms' => [
                                    [
                                        'Adults' => (int) $adults,
                                        'Children' => (int) ($children ?? 0),
                                        'ChildrenAges' => [], // Default empty, or add inputs if needed
                                    ],
                                ],
                                'ResponseTime' => 20,
                                'IsDetailedResponse' => false, // We just need availability status, not full details yet
                                'Filters' => [
                                    'Refundable' => false, // Don't restrict
                                    'NoOfRooms' => 1,
                                    'MealType' => 'All',
                                ],
                            ];

                            // Call TBO Search
                            // We use a different cache key for AVAILABILITY search
                            $searchCacheKey = 'avail_search_'.md5(json_encode($searchData));
                            $searchResponse = Cache::remember($searchCacheKey, 300, function () use ($searchData) { // 5 mins cache
                                return $this->hotelApi->searchHotel($searchData);
                            });

                            $availableHotelCodes = [];
                            if (isset($searchResponse['Status']['Code']) && $searchResponse['Status']['Code'] == 200) {
                                $results = $searchResponse['HotelResult'] ?? [];
                                foreach ($results as $res) {
                                    $code = $res['HotelCode'] ?? '';
                                    if ($code) {
                                        $availableHotelCodes[] = $code;
                                    }
                                }
                            }

                            // 3. Filter the main list to keep ONLY available hotels
                            // We override $allLightweightHotels with only the matches
                            $filteredByAvailability = [];
                            foreach ($allLightweightHotels as $h) {
                                if (in_array($h['HotelCode'], $availableHotelCodes)) {
                                    $filteredByAvailability[] = $h;
                                }
                            }

                            // Log results
                            Log::info('Availability Filter: Checked '.count($candidateCodes).', Found '.count($availableHotelCodes));

                            // Replace the list
                            $allLightweightHotels = $filteredByAvailability;

                        } catch (\Exception $e) {
                            Log::error('Availability Search Failed: '.$e->getMessage());
                            // Fallback: Do we show all or none?
                            // Usually show all with a warning, or just ignore failure and show list (safer).
                        }
                    }
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
        try {
            $request = request();
            // Increase execution time for this request to 300s for large dataset fetching
            set_time_limit(300);

            // OPTIMIZED CITY FETCHING: 80 cities for safe performance
            $allCityCodes = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->orderBy('hotels_count', 'desc')
                ->limit(80)
                ->pluck('code')
                ->toArray();

            if (empty($allCityCodes)) {
                $allCityCodes = City::whereNotNull('code')->limit(30)->pluck('code')->toArray();
            }

            $language = app()->getLocale();
            $hotels = [];

            if (! empty($allCityCodes)) {
                // Version V60 for 80-city result set
                $cacheKey = 'all_hotels_global_v60_'.$language.'_'.count($allCityCodes);
                $hotels = Cache::get($cacheKey);

                if (! $hotels) {
                    try {
                        $apiLang = $language === 'ar' ? 'ar' : 'en';

                        // Use default maxPages=1 to ENABLE concurrency (getHotelsConcurrent)
                        // This fetches cities in parallel chunks for maximum speed
                        $response = $this->hotelApi->getHotelsFromMultipleCities(
                            $allCityCodes,
                            true,
                            20, // More hotels per city for a bigger list
                            $apiLang
                        );

                        $hotels = $response['Hotels'] ?? [];

                        Log::info('Global Hotel Fetch Result:', [
                            'hotels_found' => count($hotels),
                            'cities_processed' => $response['CitiesProcessed'] ?? 0,
                            'status' => $response['Status']['Code'] ?? 'no_status',
                        ]);

                        if (! is_array($hotels)) {
                            $hotels = json_decode(json_encode($hotels), true);
                        }

                        // Only cache if we actually got results!
                        if (! empty($hotels)) {
                            \Illuminate\Support\Facades\Cache::put($cacheKey, $hotels, 86400); // 24 hours
                        } else {
                            // If empty, cache for a very short time (e.g., 1 minute) to avoid hammering API if it's down,
                            // but allow quick recovery.
                            \Illuminate\Support\Facades\Cache::put($cacheKey, [], 60);
                        }

                    } catch (\Exception $e) {
                        Log::error('Failed to fetch all hotels from API: '.$e->getMessage());
                        $hotels = [];
                    }
                }

                // Apply robust Hotel Name Translation if Arabic
                if ($language === 'ar') {
                    $translator = new \App\Services\HotelTranslationService;
                    $hotels = $translator->translateHotels($hotels);
                }
            }

            return view('Web.hotels', [
                'hotels' => $hotels, // All hotels loaded at once
                'allHotelsJson' => json_encode($hotels), // For JavaScript access
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
                'cities' => $cities,
                'countries' => $countries,
                'selectedCountryCode' => 'SA',
            ]);
        }
    }

    public function show($id)
    {
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

            // 2. Check for availability (use default dates if not provided)
            $availableRooms = [];
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');
            $guests = $request->input('guests', 1);

            // Set defaults if missing
            if (empty($checkIn) || empty($checkOut)) {
                $checkIn = Carbon::now()->addDay()->format('Y-m-d');
                $checkOut = Carbon::now()->addDays(2)->format('Y-m-d');
                $request->merge([
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guests,
                ]);
            }

            if ($checkIn && $checkOut) {
                try {
                    $searchData = [
                        'CheckIn' => $checkIn,
                        'CheckOut' => $checkOut,
                        'HotelCodes' => $id,
                        'GuestNationality' => 'AE', // Default or from request
                        'PaxRooms' => [
                            [
                                'Adults' => (int) $guests,
                                'Children' => 0,
                                'ChildrenAges' => [],
                            ],
                        ],
                        'ResponseTime' => 20,
                        'IsDetailedResponse' => true,
                        'Filters' => [
                            'Refundable' => true, // Changed to true to match Postman/expectations
                            'NoOfRooms' => 1, // Request at least 1 room
                            'MealType' => 'All',
                        ],
                    ];

                    // Fetch availability for this specific hotel
                    $searchResponse = $this->hotelApi->searchHotel($searchData); // Uses 'Search' endpoint

                    // Log the raw response for debugging
                    Log::info("Hotel Availability Response for ID {$id}:", ['status' => $searchResponse['Status']['Code'] ?? 'unknown', 'count' => count($searchResponse['HotelResult'] ?? [])]);

                    if (isset($searchResponse['Status']['Code']) && $searchResponse['Status']['Code'] == 200) {
                        if (! empty($searchResponse['HotelResult']) && is_array($searchResponse['HotelResult'])) {
                            // TBO returns a list of hotels, we expect one since we searched by ID
                            $availableRooms = $searchResponse['HotelResult'][0]['Rooms'] ?? [];
                        } elseif (! empty($searchResponse['Hotels']) && is_array($searchResponse['Hotels'])) {
                            // Fallback for different response structures
                            $availableRooms = $searchResponse['Hotels'][0]['Rooms'] ?? [];
                        }

                        // Filter rooms based on LOCAL bookings (Safety Layer)
                        // 1. Filter CONFIRMED: Normal bookings
                        // 2. Filter PENDING: Payment in progress
                        if (! empty($availableRooms)) {
                            // Count bookings that should reduce availability (CONFIRMED + PENDING)
                            $reservedRoomCounts = \App\Models\HotelBooking::where('hotel_code', $id)
                                ->whereIn('booking_status', [
                                    \App\Constants\BookingStatus::CONFIRMED,
                                    \App\Constants\BookingStatus::PENDING,
                                ])
                                ->where(function ($query) use ($checkIn, $checkOut) {
                                    $query->where('check_in', '<', $checkOut)
                                        ->where('check_out', '>', $checkIn);
                                })
                                ->selectRaw('room_name, COUNT(*) as reserved_count')
                                ->groupBy('room_name')
                                ->pluck('reserved_count', 'room_name')
                                ->toArray();

                            // Only filter if there are reserved books
                            if (! empty($reservedRoomCounts)) {
                                // Group rooms by name
                                $roomsByName = [];
                                foreach ($availableRooms as $room) {
                                    $roomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                    if (! isset($roomsByName[$roomName])) {
                                        $roomsByName[$roomName] = [];
                                    }
                                    $roomsByName[$roomName][] = $room;
                                }

                                // For each room type, reduce by the number Reserved
                                $filteredRooms = [];
                                foreach ($roomsByName as $roomName => $rooms) {
                                    $tboAvailableCount = count($rooms);
                                    $reservedCount = $reservedRoomCounts[$roomName] ?? 0;
                                    $actuallyAvailableCount = max(0, $tboAvailableCount - $reservedCount);

                                    // Add only the actually available rooms
                                    for ($i = 0; $i < $actuallyAvailableCount; $i++) {
                                        if (isset($rooms[$i])) {
                                            $filteredRooms[] = $rooms[$i];
                                        }
                                    }

                                    if ($reservedCount > 0) {
                                        Log::info('Hotel details - Room availability adjusted (Confirmed+Pending)', [
                                            'hotel' => $id,
                                            'room_type' => $roomName,
                                            'tbo_count' => $tboAvailableCount,
                                            'reserved_count' => $reservedCount,
                                            'showing_count' => $actuallyAvailableCount,
                                        ]);
                                    }
                                }

                                $availableRooms = $filteredRooms;
                            }
                        }
                    } else {
                        Log::warning("Availability check failed for Hotel ID {$id}: ".($searchResponse['Status']['Description'] ?? 'Unknown error'));
                    }

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
                'guests' => $guests,
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
        try {
            $hotelId = $request->input('hotel_id');
            $bookingCode = $request->input('booking_code');
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');
            $guests = $request->input('guests', 1);

            if (empty($hotelId) || empty($checkIn) || empty($checkOut)) {
                return redirect()->route('hotel.details', ['locale' => app()->getLocale(), 'id' => $hotelId ?? 1])
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

            // Search for room details using booking_code
            if ($bookingCode && $checkIn && $checkOut) {
                try {
                    $searchData = [
                        'CheckIn' => $checkIn,
                        'CheckOut' => $checkOut,
                        'HotelCodes' => $hotelId,
                        'GuestNationality' => 'AE',
                        'PaxRooms' => [
                            [
                                'Adults' => (int) $guests,
                                'Children' => 0,
                                'ChildrenAges' => [],
                            ],
                        ],
                        'ResponseTime' => 18,
                        'IsDetailedResponse' => true,
                        'Filters' => [
                            'Refundable' => true,
                            'NoOfRooms' => 0,
                            'MealType' => 'All',
                        ],
                    ];

                    $searchResponse = $this->hotelApi->searchHotel($searchData);

                    // Find the room with matching booking_code
                    if (isset($searchResponse['HotelResult']) && is_array($searchResponse['HotelResult'])) {
                        foreach ($searchResponse['HotelResult'] as $hotel) {
                            if (isset($hotel['Rooms']) && is_array($hotel['Rooms'])) {
                                foreach ($hotel['Rooms'] as $room) {
                                    if (isset($room['BookingCode']) && $room['BookingCode'] === $bookingCode) {
                                        $roomData = $room;
                                        $roomData['Currency'] = $hotel['Currency'] ?? 'USD';
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch room details: '.$e->getMessage());
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
            $customerEmail = auth()->check() ? auth()->user()->email : '';

            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                $totalFare = (float) $roomData['TotalFare'];
                $currency = $roomData['Currency'] ?? 'USD';
            } elseif ($request->input('total_fare')) {
                $totalFare = (float) $request->input('total_fare');
                $currency = $request->input('currency', 'USD');
            }

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
            ]);
        } catch (\Exception $e) {
            Log::error('Reservation page error: '.$e->getMessage());

            return redirect()->route('hotel.details', ['locale' => app()->getLocale(), 'id' => $request->input('hotel_id', 1)])
                ->with('error', __('Failed to load reservation page'));
        }
    }

    public function review(ReservationReviewRequest $request)
    {
        try {
            $hotelId = $request->input('hotel_id');
            $bookingCode = $request->input('booking_code');
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');
            $guests = $request->input('guests', 1);

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
                        'GuestNationality' => 'AE',
                        'PaxRooms' => [
                            [
                                'Adults' => (int) $guests,
                                'Children' => 0,
                                'ChildrenAges' => [],
                            ],
                        ],
                        'ResponseTime' => 18,
                        'IsDetailedResponse' => true,
                        'Filters' => [
                            'Refundable' => true,
                            'NoOfRooms' => 0,
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

                                    // Secondary match: Room Name (if session refreshed and code changed)
                                    $currentRoomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                    if ($requestedRoomName && $currentRoomName === $requestedRoomName) {
                                        $foundRoom = $room;
                                    }
                                }
                            }
                        }

                        if ($foundRoom) {
                            $roomData = $foundRoom;
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
                            // Update roomData and bookingCode from PreBook response if refreshed
                            if (isset($preBookResponse['BookingCode'])) {
                                $bookingCode = $preBookResponse['BookingCode'];
                                $roomData['BookingCode'] = $bookingCode;
                            }
                            Log::info('PreBook successful in review step', ['booking_code' => $bookingCode]);
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

            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                $totalFare = (float) $roomData['TotalFare'];
                $currency = $roomData['Currency'] ?? 'USD';
            } elseif ($request->input('total_fare')) {
                $totalFare = (float) $request->input('total_fare');
                $currency = $request->input('currency', 'USD');
            }

            // Determine localized names
            $currentName = $hotelDetails['HotelDetails'][0]['HotelName'] ?? $hotelDetails['Name'] ?? 'Unknown Hotel';
            $altName = $hotelDetailsAlt['HotelDetails'][0]['HotelName'] ?? $hotelDetailsAlt['Name'] ?? null;

            $hotelNameAr = $language === 'ar' ? $currentName : $altName;
            $hotelNameEn = $language === 'en' ? $currentName : $altName;

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
