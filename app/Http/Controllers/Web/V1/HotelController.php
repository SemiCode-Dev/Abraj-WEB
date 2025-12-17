<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use App\Services\Api\V1\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    protected HotelApiService $hotelApi;

    protected PaymentService $paymentService;

    public function __construct(HotelApiService $hotelApi, PaymentService $paymentService)
    {
        $this->hotelApi = $hotelApi;
        $this->paymentService = $paymentService;
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

            $response = $this->hotelApi->searchHotel($data);

            // Log response for debugging
            Log::info('Hotel search response', [
                'status_code' => $response['Status']['Code'] ?? 'unknown',
                'hotels_count' => isset($response['Hotels']) ? count($response['Hotels']) : 0,
            ]);

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
            $response = $this->hotelApi->getAllCityHotels($cityCode, true);

            $hotels = $response['Hotels'] ?? [];

            if (! is_array($hotels)) {
                $hotels = json_decode(json_encode($hotels), true);
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
                    $response = $this->hotelApi->getAllCityHotels($cityCode, true);

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
            // Get current page from request
            $page = (int) request('page', 1);
            $perPage = 12; // Hotels per page

            // Get city codes from database
            $cityCodes = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->pluck('code')
                ->toArray();

            if (empty($cityCodes)) {
                return view('Web.hotels', [
                    'hotels' => [],
                    'currentPage' => 1,
                    'totalPages' => 1,
                    'totalHotels' => 0,
                    'perPage' => $perPage,
                ]);
            }

            // Use cache key based on city codes count (changes when cities are added/removed)
            $cacheKey = 'all_hotels_'.md5(implode(',', $cityCodes));

            // Cache for 24 hours - hotels don't change frequently
            $allHotels = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($cityCodes) {
                try {
                    // Get hotels from all cities using TBOHotelCodeList
                    // Limit to 5 hotels per city for faster loading (can be increased if needed)
                    $response = $this->hotelApi->getHotelsFromMultipleCities($cityCodes, true, 5);

                    $hotels = $response['Hotels'] ?? [];

                    if (! is_array($hotels)) {
                        $hotels = json_decode(json_encode($hotels), true);
                    }

                    return $hotels;
                } catch (\Exception $e) {
                    Log::error('Failed to fetch all hotels from API: '.$e->getMessage());

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
            $response = $this->hotelApi->getCitiesByCountry($countryCode);

            // Handle different possible response structures
            $cities = [];
            if (isset($response['CityList']) && is_array($response['CityList'])) {
                $cities = $response['CityList'];
            } elseif (is_array($response) && isset($response[0])) {
                $cities = $response;
            }

            // Transform the response to match expected format
            $formattedCities = collect($cities)->map(function ($city) {
                return [
                    'Name' => $city['CityName'] ?? $city['Name'] ?? '',
                    'Name_ar' => $city['CityName'] ?? $city['Name'] ?? '', // TBO API might not have Arabic name
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
            // Get hotel details from API
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $hotelDetails = $this->hotelApi->getHotelDetails($id, $language);

            // Log response for debugging
            Log::info('Hotel details response', [
                'hotel_id' => $id,
                'status_code' => $hotelDetails['Status']['Code'] ?? 'unknown',
            ]);

            return view('Web.hotel-details', [
                'hotelId' => $id,
                'hotelDetails' => $hotelDetails,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch hotel details: '.$e->getMessage());

            // Return view with error message
            return view('Web.hotel-details', [
                'hotelId' => $id,
                'hotelDetails' => null,
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

            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                $totalFare = (float) $roomData['TotalFare'];
                $currency = $roomData['Currency'] ?? 'USD';
            } elseif ($request->input('total_fare')) {
                $totalFare = (float) $request->input('total_fare');
                $currency = $request->input('currency', 'USD');
            }

            // Get customer email (from form or authenticated user)
            $customerEmail = auth()->check() ? auth()->user()->email : $request->input('email', 'guest@example.com');

            // Generate payment data (use original currency and amount)
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $totalFare,
                'currency' => $currency,
                'customer_email' => $customerEmail,
                'merchant_reference' => 'hotel_'.$hotelId.'_'.uniqid(),
            ]);

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
            ]);
        } catch (\Exception $e) {
            Log::error('Reservation page error: '.$e->getMessage());

            return redirect()->route('hotel.details', ['locale' => app()->getLocale(), 'id' => $request->input('hotel_id', 1)])
                ->with('error', __('Failed to load reservation page'));
        }
    }
}
