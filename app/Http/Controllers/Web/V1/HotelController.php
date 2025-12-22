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

            // Create cache key based on search parameters
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $data['Language'] = $language;
            $cacheKey = 'hotel_search_'.md5(json_encode($data));

            // Cache search results for 1 hour (3600 seconds)
            $response = Cache::remember($cacheKey, 3600, function () use ($data) {
                return $this->hotelApi->searchHotel($data);
            });

            // Log response for debugging
            Log::info('Hotel search response', [
                'status_code' => $response['Status']['Code'] ?? 'unknown',
                'hotels_count' => isset($response['Hotels']) ? count($response['Hotels']) : 0,
                'cached' => Cache::has($cacheKey),
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
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
            $response = $this->hotelApi->getAllCityHotels($cityCode, true, $language);

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
                    $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
                    $response = $this->hotelApi->getHotelsFromMultipleCities($cityCodes, true, 5, $language);

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
                    'guests' => $guests
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
                        if (!empty($searchResponse['HotelResult']) && is_array($searchResponse['HotelResult'])) {
                            // TBO returns a list of hotels, we expect one since we searched by ID
                            $availableRooms = $searchResponse['HotelResult'][0]['Rooms'] ?? [];
                        } elseif (!empty($searchResponse['Hotels']) && is_array($searchResponse['Hotels'])) {
                             // Fallback for different response structures
                             $availableRooms = $searchResponse['Hotels'][0]['Rooms'] ?? [];
                        }
                    } else {
                        Log::warning("Availability check failed for Hotel ID {$id}: " . ($searchResponse['Status']['Description'] ?? 'Unknown error'));
                    }

                } catch (\Exception $e) {
                    Log::error("Error fetching room availability: " . $e->getMessage());
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
     * Helper method to translate cities to Arabic manually
     */
    private function translateCities(array $cities): array
    {
        $arabicMap = [
            'DXB' => 'دبي',
            'AUH' => 'أبو ظبي',
            'RUH' => 'الرياض',
            'JED' => 'جدة',
            'DMM' => 'الدمام',
            'MED' => 'المدينة المنورة',
            'MAK' => 'مكة المكرمة',
            'CAI' => 'القاهرة',
            'IST' => 'اسطنبول',
            'LON' => 'لندن',
            'PAR' => 'باريس',
            'AMM' => 'عمان',
            'KWI' => 'الكويت',
            'DOH' => 'الدوحة',
            'MCT' => 'مسقط',
            'BAH' => 'المنامة',
            'BEI' => 'بيروت',
            'CAS' => 'الدار البيضاء',
            'RAK' => 'مراكش',
            'TUN' => 'تونس',
            'ALG' => 'الجزائر',
            'KRT' => 'الخرطوم',
            'SAN' => 'صنعاء',
            'DAM' => 'دمشق',
            'BAG' => 'بغداد',
            'Makkah' => 'مكة المكرمة',
            'MAK' => 'مكة المكرمة',
            'Al Madinah' => 'المدينة المنورة',
            'Madinah' => 'المدينة المنورة',
            'MED' => 'المدينة المنورة',
            'Al Khobar' => 'الخبر',
            'Khobar' => 'الخبر',
            'DMM' => 'الدمام',
            'Dhahran' => 'الظهران',
            'Abha' => 'أبها',
            'Taif' => 'الطائف',
            'Tabuk' => 'تبوك',
            'Buraidah' => 'بريدة',
            'Hail' => 'حائل',
            'Najran' => 'نجران',
            'Jizan' => 'جيزان',
            'Al Bahah' => 'الباحة',
            'Sakaka' => 'سكاكا',
            'Arar' => 'عرعر',
            'Afif' => 'عفيف',
            'Al Bukayriyah' => 'البكيرية',
            'Al Majma\'ah' => 'المجمعة',
            'Al Lith' => 'الليث',
            'Al Lith Makkah' => 'الليث',
            'Al Qunfudhah' => 'القنفذة',
            'Al Qunfudhah Makkah' => 'القنفذة',
            'Al Wajh' => 'الوجه',
            'Al Lith, Makkah' => 'الليث',
            'Al Qunfudhah, Makkah' => 'القنفذة',
            'Al Madinah Province' => 'منطقة المدينة المنورة',
            'Yanbu' => 'ينبع',
            'Yanbu Al Bahr' => 'ينبع البحر',
        ];

        foreach ($cities as &$city) {
            $code = $city['CityCode'] ?? $city['Code'] ?? '';
            $name = $city['CityName'] ?? $city['Name'] ?? '';

            // Debug logging
            Log::info("Translating City: Code=[$code], Name=[$name]");

            // Explicit overrides for problematic matches
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
            if (stripos($name, 'Al Madinah Province') !== false) {
                 $city['CityName'] = 'منطقة المدينة المنورة';
                 $city['Name'] = 'منطقة المدينة المنورة';
                 continue;
            }

            if (isset($arabicMap[$code])) {
                $city['CityName'] = $arabicMap[$code];
                $city['Name'] = $arabicMap[$code];
            } elseif (isset($arabicMap[$name])) {
                $city['CityName'] = $arabicMap[$name];
                $city['Name'] = $arabicMap[$name];
            } else {
                // Try tougher matching: Case-insensitive and trimmed
                foreach ($arabicMap as $key => $value) {
                    if (strcasecmp(trim($name), trim($key)) === 0) {
                        $city['CityName'] = $value;
                        $city['Name'] = $value;
                        break;
                    }
                }
            }
        }

        return $cities;
    }
}
