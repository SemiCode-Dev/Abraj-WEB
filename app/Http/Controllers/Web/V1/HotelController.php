<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\ReservationReviewRequest;
use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use App\Services\Api\V1\PaymentService;
use App\Services\Api\V1\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function __construct(HotelApiService $hotelApi, PaymentService $paymentService, BookingService $bookingService)
    {
        $this->hotelApi = $hotelApi;
        $this->paymentService = $paymentService;
        $this->bookingService = $bookingService;
    }

    public function search(Request $request)
    {
        try {
            $hotelCodes = $request->input('HotelCodes');

            // Validate HotelCodes
            if (empty($hotelCodes)) {
                return response()->json([
                    'Status' => [
                        'Code' => 400,
                        'Description' => 'Hotel Codes can not be null or empty',
                    ],
                ], 400);
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
                return response()->json([
                    'Status' => [
                        'Code' => 400,
                        'Description' => 'CheckIn and CheckOut dates are required',
                    ],
                ], 400);
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
                                \App\Constants\BookingStatus::PENDING
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
                        if (!empty($reservedRoomCounts)) {
                            // Group rooms by name to count how many TBO returned
                            $roomsByName = [];
                            foreach ($hotel['Rooms'] as $room) {
                                $roomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                if (!isset($roomsByName[$roomName])) {
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
                                    Log::info("Room availability adjusted (Confirmed+Pending)", [
                                        'hotel' => $hotelCode,
                                        'room_type' => $roomName,
                                        'tbo_count' => $tboAvailableCount,
                                        'reserved_count' => $reservedCount,
                                        'showing_count' => $actuallyAvailableCount
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
            // Get all hotels from all pages
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $response = $this->hotelApi->getAllCityHotels($cityCode, true, $language);

            $hotels = $response['Hotels'] ?? [];

            if (! is_array($hotels)) {
                $hotels = json_decode(json_encode($hotels), true);
            }

            // Apply robust Hotel Name Translation if Arabic
            if ($language === 'ar') {
                $translator = new \App\Services\HotelTranslationService();
                $hotels = $translator->translateHotels($hotels);
            }

            $formattedHotels = collect($hotels)->map(function ($hotel) {
                return [
                    'HotelCode' => $hotel['HotelCode'] ?? '',
                    'HotelName' => $hotel['HotelName'] ?? '',
                    'HotelRating' => $hotel['HotelRating'] ?? '',
                    'Address' => $hotel['Address'] ?? '',
                    'CityName' => $hotel['CityName'] ?? '',
                    'CountryName' => $hotel['CountryName'] ?? '',
                    'ImageUrl' => isset($hotel['ImageUrls'][0]['ImageUrl']) ? $hotel['ImageUrls'][0]['ImageUrl'] : '',
                ];
            })->filter(function ($hotel) {
                return ! empty($hotel['HotelCode']);
            });

            return response()->json($formattedHotels->values());
        } catch (\Exception $e) {
            Log::error('Failed to fetch hotels from TBO API: '.$e->getMessage());

            return response()->json(['error' => __('Failed to fetch hotels')], 500);
        }
    }

    public function getCityHotels($cityCode)
    {
        try {
            // Get current page from request
            $page = (int) request('page', 1);
            $perPage = 12; // Hotels per page

            // Cache key for this specific city
            $cacheKey = 'city_hotels_'.$cityCode;

            // Cache for 24 hours - hotels don't change frequently
            $allHotels = Cache::remember($cacheKey, 86400, function () use ($cityCode) {
                try {
                    // Get all hotels from all pages
                    $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
                    $response = $this->hotelApi->getAllCityHotels($cityCode, true, $language);

                    $hotels = $response['Hotels'] ?? [];

                    if (! is_array($hotels)) {
                        $hotels = json_decode(json_encode($hotels), true);
                    }

                    return $hotels;
                } catch (\Exception $e) {
                    Log::error('Failed to fetch city hotels from API: '.$e->getMessage());

                    return [];
                }
            });

            // Calculate pagination
            $totalHotels = count($allHotels);
            $totalPages = (int) ceil($totalHotels / $perPage);
            $offset = ($page - 1) * $perPage;

            // Get hotels for current page
            $hotels = array_slice($allHotels, $offset, $perPage);

            return view('Web.hotels', [
                'hotels' => $hotels,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalHotels' => $totalHotels,
                'perPage' => $perPage,
                'cityCode' => $cityCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch city hotels: '.$e->getMessage());

            return view('Web.hotels', [
                'hotels' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalHotels' => 0,
                'perPage' => 12,
                'cityCode' => $cityCode,
            ]);
        }
    }

    public function getAllHotels()
    {
        try {
            // Increase execution time for this request to 120s as a safety net
            set_time_limit(120);

            // Get current page from request
            $page = (int) request('page', 1);
            $citiesPerPage = 4; // Fetch hotels from 4 cities per page (4 * 5 = ~20 hotels)

            // Get all city codes from database (Fast local query)
            $allCityCodes = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->orderBy('name', 'asc') // Consistent ordering for pagination
                ->pluck('code')
                ->toArray();

            if (empty($allCityCodes)) {
                return view('Web.hotels', [
                    'hotels' => [],
                    'currentPage' => 1,
                    'totalPages' => 1,
                    'totalHotels' => 0,
                    'perPage' => 12,
                ]);
            }

            // Calculate pagination based on CITIES
            $totalCities = count($allCityCodes);
            $totalPages = (int) ceil($totalCities / $citiesPerPage);
            
            // Ensure page is valid
            if ($page < 1) $page = 1;
            if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

            // Get cities for current page
            $offset = ($page - 1) * $citiesPerPage;
            $currentCityCodes = array_slice($allCityCodes, $offset, $citiesPerPage);

            // Use cache key based on CURRENT page cities and LANGUAGE
            // This prevents fetching 66 cities at once and prevents language mixups
            $language = app()->getLocale();
            $cacheKey = 'hotels_page_'.$page.'_'.$language.'_'.md5(implode(',', $currentCityCodes));

            // Cache for 24 hours
            $hotels = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($currentCityCodes, $language) {
                try {
                    // Fetch from API only for the 4 cities of this page
                    $apiLang = $language === 'ar' ? 'ar' : 'en';
                    // We fetch 5 hotels per city, so ~20 hotels total
                    $response = $this->hotelApi->getHotelsFromMultipleCities($currentCityCodes, true, 5, $apiLang);

                    $hotels = $response['Hotels'] ?? [];

                    if (! is_array($hotels)) {
                        $hotels = json_decode(json_encode($hotels), true);
                    }

                    return $hotels;
                } catch (\Exception $e) {
                    Log::error('Failed to fetch page hotels from API: '.$e->getMessage());
                    return [];
                }
            });

            // Apply robust Hotel Name Translation if Arabic
            if ($language === 'ar') {
                $translator = new \App\Services\HotelTranslationService();
                $hotels = $translator->translateHotels($hotels);
            }

            // Count is approximate (Total Cities * Avg Hotels/City) or just hide total
            // For UI consistency, we can show total cities or "many"
            $estimatedTotalHotels = $totalCities * 5; 

            return view('Web.hotels', [
                'hotels' => $hotels, // Already sliced by API logic effectively
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalHotels' => $estimatedTotalHotels,
                'perPage' => count($hotels), // Dynamic per page
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch all hotels: '.$e->getMessage());

            return view('Web.hotels', [
                'hotels' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalHotels' => 0,
                'perPage' => 12,
            ]);
        }
    }

    public function getCitiesByCountry($countryCode)
    {
        try {
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $response = $this->hotelApi->getCitiesByCountry($countryCode, $language);

            // Handle different possible response structures
            $cities = [];
            if (isset($response['CityList']) && is_array($response['CityList'])) {
                $cities = $response['CityList'];
            } elseif (is_array($response) && isset($response[0])) {
                $cities = $response;
            }

            // Manual translation and filtering
            if (app()->getLocale() === 'ar') {
                $cities = $this->translateCities($cities);
            }

            // Transform the response to match expected format
            $formattedCities = collect($cities)->map(function ($city) {
                return [
                    'Name' => $city['CityName'] ?? $city['Name'] ?? '',
                    'Name_ar' => $city['CityName'] ?? $city['Name'] ?? '',
                    'Code' => $city['CityCode'] ?? $city['Code'] ?? '',
                ];
            })->filter(function ($city) {
                return ! empty($city['Code']);
            });

            return response()->json($formattedCities->values());
        } catch (\Exception $e) {
            Log::error('Failed to fetch cities from TBO API: '.$e->getMessage());

            return response()->json(['error' => 'Failed to fetch cities'], 500);
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
                        if (!empty($availableRooms)) {
                            // Count bookings that should reduce availability (CONFIRMED + PENDING)
                            $reservedRoomCounts = \App\Models\HotelBooking::where('hotel_code', $id)
                                ->whereIn('booking_status', [
                                    \App\Constants\BookingStatus::CONFIRMED,
                                    \App\Constants\BookingStatus::PENDING
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
                            if (!empty($reservedRoomCounts)) {
                                // Group rooms by name
                                $roomsByName = [];
                                foreach ($availableRooms as $room) {
                                    $roomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
                                    if (!isset($roomsByName[$roomName])) {
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
                                        Log::info("Hotel details - Room availability adjusted (Confirmed+Pending)", [
                                            'hotel' => $id,
                                            'room_type' => $roomName,
                                            'tbo_count' => $tboAvailableCount,
                                            'reserved_count' => $reservedCount,
                                            'showing_count' => $actuallyAvailableCount
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
            $guestNotes = $request->input('notes', '');

            // If user is authenticated, use their data if form data is not provided
            if (auth()->check()) {
                $user = auth()->user();
                $guestName = $guestName ?: $user->name;
                $guestEmail = $guestEmail ?: $user->email;
                $guestPhone = $guestPhone ?: ($user->phone ?? '');
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
                            Log::warning('PreBook failed in review step: ' . $errorMsg);
                            throw new \Exception(__('The selected room is no longer available: ') . $errorMsg);
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

            // Prepare data for booking creation
            $bookingData = [
                'hotel_code' => $hotelId,
                'hotel_name' => $hotelDetails['HotelDetails'][0]['HotelName'] ?? $hotelDetails['Name'] ?? 'Unknown Hotel',
                'room_code' => $bookingCode,
                'room_name' => is_array($roomData['Name']) ? ($roomData['Name'][0] ?? 'Room') : ($roomData['Name'] ?? 'Room'),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $totalFare,
                'currency' => $currency,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_phone' => $guestPhone,
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
                'guestNotes' => $guestNotes,
            ]);
        } catch (\Exception $e) {
            Log::error('Review page error: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('reservation', ['locale' => app()->getLocale()])
                ->withInput()
                ->with('error', __('Failed to load review page') . ': ' . $e->getMessage());
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
            if (!empty($name)) {
                $names[] = trim($name);
            }
        }

        // Use the service to batch translate
        // This handles: Check Local DB -> Google Translate if missing -> Save Local DB
        $translationService = new \App\Services\CityTranslationService();
        $translatedNames = $translationService->translateBatch($names, 5); // Limit 5 API calls per request

        foreach ($cities as &$city) {
            $name = $city['CityName'] ?? $city['Name'] ?? '';
            if (empty($name)) continue;

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
