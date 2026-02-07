<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\HotelBookingRequest;
use App\Http\Requests\Api\ReservationReviewRequest;
use App\Models\City;
use App\Services\Api\V1\BookingService;
use App\Services\Api\V1\HotelApiService;
use App\Services\Api\V1\PaymentService;
use App\Services\Hotel\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function __construct(
        protected HotelApiService $hotelApi,
        protected AvailabilityService $availabilityService,
        protected BookingService $bookingService,
        protected PaymentService $paymentService
    ) {}

    private function successResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function errorResponse($message = 'Error', $status = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $status);
    }

    /**
     * Dedicated API Endpoint for Hotel Search
     * Strict Availability Logic.
     */
    public function index(Request $request)
    {
        set_time_limit(600); // Match Web Controller (600s)

        // 1. Localization (Header ONLY)
        $lang = $request->header('Accept-Language');
        if ($lang && str_contains(strtolower($lang), 'ar')) {
            app()->setLocale('ar');
        } else {
            app()->setLocale('en'); // Default
        }
        $language = app()->getLocale();

        try {
            // 2. Resolve Candidate Hotels (Scope)
            $allCityCodes = City::whereNotNull('code')
                ->where('code', '!=', '')
                ->orderByRaw('CASE WHEN country_id = 151 THEN 0 ELSE 1 END') // Prioritize Saudi cities
                ->orderBy('hotels_count', 'desc')
                ->limit(500) // Increased for exhaustive search
                ->pluck('code')
                ->toArray();

            if (empty($allCityCodes)) {
                return $this->successResponse([
                    'items' => [],
                    'pagination' => $this->emptyPagination(),
                ], __('No cities found.'));
            }

            // Fetch Candidate Hotels (Lightweight)
            $cacheKey = 'api_hotels_candidates_'.$language.'_'.md5(json_encode($allCityCodes));
            $candidates = Cache::remember($cacheKey, 86400, function () use ($allCityCodes, $language) {
                try {
                    $response = $this->hotelApi->getHotelsFromMultipleCities(
                        $allCityCodes,
                        true,
                        null,
                        $language,
                        2 // Optimized to 2 pages for extremely fast initial surge
                    );
                    $h = $response['Hotels'] ?? [];

                    return is_array($h) ? $h : json_decode(json_encode($h), true);
                } catch (\Exception $e) {
                    return [];
                }
            });

            if (empty($candidates)) {
                return $this->successResponse([
                    'items' => [],
                    'pagination' => $this->emptyPagination(),
                ], __('No hotels found matching criteria.'));
            }

            // 3. Pre-Filter Candidates (Metadata)
            // Name Search
            if ($request->has('search') && ! empty($request->input('search'))) {
                $search = mb_strtolower(trim($request->input('search')));
                $candidates = array_filter($candidates, function ($h) use ($search) {
                    $name = mb_strtolower($h['HotelName'] ?? $h['Name'] ?? '');

                    return str_contains($name, $search);
                });
            }

            // Stars Filter
            if ($request->has('stars')) {
                $stars = (array) $request->input('stars');
                if (! empty($stars)) {
                    $candidates = array_filter($candidates, function ($h) use ($stars) {
                        // $rating = ($h['HotelRating'] ?? $h['Rating'] ?? 0);
                        $rating = $this->getHotelRating($h['HotelRating']);

                        return in_array($rating, $stars);
                    });
                }
            }

            if (empty($candidates)) {
                return $this->successResponse([
                    'items' => [],
                    'pagination' => $this->emptyPagination(),
                ], __('No hotels found after filtering.'));
            }

            // 4. Availability Check (STRICT)
            // Default Dates if not provided (Tomorrow -> +1 Day)
            $checkIn = \Carbon\Carbon::tomorrow()->format('Y-m-d');
            $checkOut = \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d');

            // Default Pax (2 Adults)
            $paxRooms = [[
                'Adults' => 2,
                'Children' => 0,
                'ChildrenAges' => [],
            ]];

            $codesToCheck = array_values(array_unique(array_column($candidates, 'HotelCode')));

            // One unified availability call for the whole surge
            $availableData = $this->availabilityService->checkBatchAvailability(
                $codesToCheck,
                $checkIn,
                $checkOut,
                $paxRooms,
                'SA',
                false // Lightweight
            );

            // 5. Build Valid Hotel List and Convert Currency
            // Use local cache for the ENTIRE result set to make pagination/sorting instant
            $finalCacheKey = 'api_hotels_final_'.$language.'_'.md5(json_encode($request->all()).$checkIn.$checkOut);
            $validHotels = Cache::remember($finalCacheKey, 600, function () use ($candidates, $availableData) {
                $hList = [];
                $targetCurrency = \App\Helpers\CurrencyHelper::getCurrentCurrency();
                foreach ($candidates as $h) {
                    $code = $h['HotelCode'] ?? '';
                    if (isset($availableData[$code])) {
                        $result = $availableData[$code];
                        if ($result->isAvailable()) {
                            $priceInUsd = $result->minPrice;
                            $h['MinPrice'] = \App\Helpers\CurrencyHelper::convert($priceInUsd, $targetCurrency);
                            $h['Currency'] = $targetCurrency;
                            $hList[] = $h;
                        }
                    }
                }

                return $hList;
            });

            // 5a. Facilities Filter
            $reqFacilities = $request->input('facilities');
            if (! empty($reqFacilities) && is_array($reqFacilities)) {
                $validHotels = array_values(array_filter($validHotels, function ($h) use ($reqFacilities) {
                    $normalized = $this->normalizeFacilities($h);
                    foreach ($reqFacilities as $facility) {
                        // Check if the requested facility exists in our normalized list and is true
                        if (empty($normalized[$facility])) {
                            return false;
                        }
                    }

                    return true;
                }));
            }

            // 6. Sorting
            $sort = $request->input('sort');
            if ($sort === 'price_asc') {
                usort($validHotels, fn ($a, $b) => ($a['MinPrice'] ?? 0) <=> ($b['MinPrice'] ?? 0));
            } elseif ($sort === 'price_desc') {
                usort($validHotels, fn ($a, $b) => ($b['MinPrice'] ?? 0) <=> ($a['MinPrice'] ?? 0));
            }

            // 7. Pagination (IMPORTANT: Before heavy detail fetching)
            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 10);
            $total = count($validHotels);
            $lastPage = (int) ceil($total / $perPage);

            $offset = ($page - 1) * $perPage;
            $items = array_slice($validHotels, $offset, $perPage);

            // 8. Fetch Details ONLY for the Paginated Results
            // This is the CRITICAL optimization to avoid sequential API timeouts.
            $pagedCodes = array_column($items, 'HotelCode');
            $detailsMap = [];

            if (! empty($pagedCodes)) {
                $cStr = implode(',', $pagedCodes);
                $dCacheKey = 'api_details_paged_'.$language.'_'.md5($cStr);
                $detailsMap = Cache::remember($dCacheKey, 86400, function () use ($cStr, $language) {
                    try {
                        $resp = $this->hotelApi->getHotelDetails($cStr, $language);
                        $list = $resp['HotelDetails'] ?? $resp['HotelResult'] ?? [];
                        $m = [];
                        if (is_array($list)) {
                            foreach ($list as $item) {
                                $id = $item['HotelCode'] ?? $item['Code'] ?? '';
                                if ($id) {
                                    $m[$id] = $item;
                                }
                            }
                        }

                        return $m;
                    } catch (\Exception $e) {
                        return [];
                    }
                });
            }

            // 9. Format Response with Merged Details
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');

            $data = array_map(function ($h) use ($detailsMap, $checkIn, $checkOut) {
                $code = $h['HotelCode'] ?? '';
                $details = $detailsMap[$code] ?? [];

                // Merge static details if available for facilities
                $fullHotel = array_merge($h, $details);
                $facilities = $this->normalizeFacilities($fullHotel);

                return [
                    'hotel_code' => (string) ($h['HotelCode'] ?? ''),
                    'hotel_name' => (string) ($h['HotelName'] ?? $h['Name'] ?? ''),
                    'rating' => $this->getHotelRating($h['HotelRating']),
                    'min_price' => (float) ($h['MinPrice'] ?? 0),
                    'room_price_per_night' => $this->calculatePricePerNight((float) ($h['MinPrice'] ?? 0), $checkIn, $checkOut), // Calculated field
                    'currency' => (string) ($h['Currency'] ?? 'USD'),
                    'source' => 'tbo',
                    'tags' => [ // Added tags for UI badges
                        'room_only' => true, // Default for TBO standard search unless meal is specified
                        'refundable' => false,
                    ],
                    'facilities' => $facilities,
                    'address' => (string) ($details['Address'] ?? $h['Address'] ?? ''),
                    'description' => (string) ($details['Description'] ?? ''),
                    'image' => (string) ($details['HotelImage'] ?? $details['Image'] ?? ($details['Images'][0] ?? 'https://abrajstay.com/images/default.jpg')),
                    'cancellation_policy' => (string) __('Free Cancellation'),
                ];
            }, $items);

            return $this->successResponse([
                'items' => $data,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => $lastPage,
                ],
            ], __('Hotels fetched successfully.'));

        } catch (\Exception $e) {
            Log::error('API Hotel Error: '.$e->getMessage());

            return $this->errorResponse(__('Server Error').': '.$e->getMessage(), 500);
        }
    }

    private function calculatePricePerNight(float $totalPrice, ?string $checkIn, ?string $checkOut): float
    {
        if (! $checkIn || ! $checkOut || $totalPrice <= 0) {
            return $totalPrice; // Fallback or return 0
        }

        try {
            $in = \Carbon\Carbon::parse($checkIn);
            $out = \Carbon\Carbon::parse($checkOut);
            $nights = $in->diffInDays($out);

            if ($nights < 1) {
                $nights = 1;
            }

            return round($totalPrice / $nights, 2);
        } catch (\Exception $e) {
            return $totalPrice;
        }
    }

    private function emptyPagination()
    {
        return [
            'current_page' => 1,
            'per_page' => 10,
            'total' => 0,
            'last_page' => 1,
        ];
    }

    private function normalizeFacilities($hotel)
    {
        $raw = $hotel['HotelFacilities'] ?? $hotel['Facilities'] ?? [];
        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }
        $rawStr = is_array($raw) ? implode(' ', array_map(function ($f) {
            return is_array($f) ? ($f['Name'] ?? '') : $f;
        }, $raw)) : '';

        $rawLower = mb_strtolower($rawStr);

        return [
            'free_wifi' => str_contains($rawLower, 'wifi') || str_contains($rawLower, 'internet'),
            'pool' => str_contains($rawLower, 'pool') || str_contains($rawLower, 'swim'),
            'restaurant' => str_contains($rawLower, 'restaurant') || str_contains($rawLower, 'dining'),
            'spa' => str_contains($rawLower, 'spa') || str_contains($rawLower, 'sauna') || str_contains($rawLower, 'massage'),
            'gym' => str_contains($rawLower, 'gym') || str_contains($rawLower, 'fitness'),
            'parking' => str_contains($rawLower, 'park') || str_contains($rawLower, 'valet'),
        ];
    }

    /**
     * Get Hotel Details & Live Availability
     */
    public function show(Request $request, $code)
    {
        set_time_limit(120);

        // 1. Localization
        $lang = $request->header('Accept-Language');
        if ($lang && str_contains(strtolower($lang), 'ar')) {
            app()->setLocale('ar');
        } else {
            app()->setLocale('en');
        }
        $language = app()->getLocale();

        try {
            // 2. Fetch Static Hotel Details
            $cacheKey = "api_hotel_details_{$code}_{$language}";
            // We cache static details heavily (24h)
            $details = Cache::remember($cacheKey, 86400, function () use ($code, $language) {
                try {
                    $response = $this->hotelApi->getHotelDetails($code, $language);
                    // Allow for variations in TBO response structure
                    $d = $response['HotelDetails'] ?? $response['HotelResult'] ?? [];

                    return is_array($d) ? ($d[0] ?? $d) : $d;
                } catch (\Exception $e) {
                    return null;
                }
            });

            if (! $details || empty($details)) {
                return $this->errorResponse(__('Hotel not found'), 404);
            }

            // 3. Live Availability Check
            // Params
            $checkIn = $request->input('check_in', $request->input('check in'));
            $checkOut = $request->input('check_out', $request->input('check out'));
            $roomsCount = (int) $request->input('rooms', 1);
            $adults = (int) $request->input('adults', 1);
            $children = (int) $request->input('children', 0);
            $childrenAges = $request->input('children_ages', []);
            if (! is_array($childrenAges)) {
                $childrenAges = [];
            }

            $availableRooms = [];
            $availabilityStatus = 'available';

            if (! empty($checkIn) && ! empty($checkOut)) {
                // Pax Validation & Cleanup
                if ($adults < $roomsCount) {
                    $adults = $roomsCount;
                }

                // Normalize Children Ages Input
                if (empty($childrenAges)) {
                    $childrenAges = $request->input('children_age')
                        ?? $request->input('children.age')
                        ?? $request->input('child_ages')
                        ?? [];
                }
                if (! is_array($childrenAges)) {
                    if (is_string($childrenAges) && str_contains($childrenAges, ',')) {
                        $childrenAges = explode(',', $childrenAges);
                    } else {
                        $childrenAges = [$childrenAges];
                    }
                }
                $childrenAges = array_values(array_filter($childrenAges, fn ($a) => is_numeric($a)));

                // 3. Prepare Pax Logic (Distribution)
                $paxRooms = [];
                for ($i = 0; $i < $roomsCount; $i++) {
                    $roomAdults = (int) ceil($adults / $roomsCount);
                    $roomChildren = (int) ceil($children / $roomsCount);

                    $roomChildAges = [];
                    if ($roomChildren > 0) {
                        for ($k = 0; $k < $roomChildren; $k++) {
                            // Take from list or empty (will be filled by cleanup logic)
                            $val = array_shift($childrenAges);
                            if ($val !== null) {
                                $roomChildAges[] = $val;
                            }
                        }
                    }

                    $paxRooms[] = [
                        'Adults' => $roomAdults,
                        'Children' => $roomChildren,
                        'ChildrenAges' => $roomChildAges,
                    ];
                }

                // 4. CLEANING LOGIC (COPIED FROM WEB CONTROLLER)
                $cleanedPaxRooms = [];
                foreach ($paxRooms as $room) {
                    $adultsP = filter_var($room['Adults'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
                    $childrenP = filter_var($room['Children'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);

                    $childrenAgesP = $room['ChildrenAges'] ?? [];
                    if (! is_array($childrenAgesP)) {
                        $childrenAgesP = [];
                    }

                    // Cast ages to integers and clamp to 0-12 (matching client requirements)
                    $childrenAgesP = array_map(function ($age) {
                        $ageInt = (int) $age;

                        return max(0, min(12, $ageInt));
                    }, $childrenAgesP);

                    if ($childrenP > count($childrenAgesP)) {
                        for ($i = count($childrenAgesP); $i < $childrenP; $i++) {
                            $childrenAgesP[] = 0; // Default 0
                        }
                    } elseif ($childrenP < count($childrenAgesP)) {
                        $childrenAgesP = array_slice($childrenAgesP, 0, $childrenP);
                    }

                    $cleanedPaxRooms[] = [
                        'Adults' => $adultsP,
                        'Children' => $childrenP,
                        'ChildrenAges' => $childrenAgesP,
                    ];
                }
                $paxRooms = $cleanedPaxRooms;

                // Clean up Pax Structure
                $paxRooms = array_values($paxRooms);

                // Availability Call
                try {
                    $availability = $this->availabilityService->checkAvailability(
                        $code,
                        $checkIn,
                        $checkOut,
                        $paxRooms,
                        'SA',
                        true // IsDetailed
                    );

                    if ($availability->isAvailable()) {
                        $availableRooms = $availability->rooms;
                    } else {
                        $availabilityStatus = 'no_rooms';
                    }
                } catch (\Exception $e) {
                    Log::error('Availability Check Error: '.$e->getMessage());
                    $availabilityStatus = 'error';
                }
            } else {
                $availabilityStatus = 'no_search_criteria';
            }

            // 4. Format Rooms
            $formattedRooms = [];
            foreach ($availableRooms as $room) {
                // Ensure room price is converted
                $amount = \App\Helpers\CurrencyHelper::convert((float) ($room['TotalFare'] ?? $room['Price']['PublishedPrice'] ?? 0));

                $formattedRooms[] = [
                    'room_code' => (string) ($room['BookingCode'] ?? uniqid()),
                    'room_name' => (string) ($room['Name'][0] ?? $room['Name'] ?? 'Room'),
                    'image' => 'https://abrajstay.com/images/default.jpg', // TBO rooms often don't have specific images
                    'price' => [
                        'amount' => $amount,
                        'currency' => \App\Helpers\CurrencyHelper::getCurrentCurrency(),
                    ],
                    'room_price_per_night' => $this->calculatePricePerNight($amount, $checkIn, $checkOut),
                    'tags' => [
                        'room_only' => true, // Default for TBO standard search
                        'refundable' => ! ($room['NonRefundable'] ?? false),
                    ],
                    'refundable' => ! ($room['NonRefundable'] ?? false),
                    'available' => true,
                ];
            }

            // 5. Format Response
            $response = [
                'hotel' => [
                    'hotel_code' => (string) ($details['HotelCode'] ?? $code),
                    'name' => (string) ($details['HotelName'] ?? $details['Name'] ?? ''),
                    'address' => (string) ($details['Address'] ?? ''),
                    'rating' => (int) ($details['HotelRating'] ?? $details['Rating'] ?? 0),
                    'source' => 'tbo',
                    'contact' => [
                        'phone' => (string) ($details['PhoneNumber'] ?? ''),
                        'email' => (string) ($details['Email'] ?? ''),
                    ],
                ],
                'about_hotel' => (string) ($details['Description'] ?? ''),
                'facilities' => $this->normalizeFacilitiesList($details),
                'images' => (array) ($details['Images'] ?? []),
                'rooms' => $formattedRooms,
            ];

            if (empty($formattedRooms)) {
                $response['availability_status'] = $availabilityStatus;
            }

            return $this->successResponse($response, __('Hotel details fetched successfully.'));

        } catch (\Exception $e) {
            Log::error('API Hotel Details Error: '.$e->getMessage());

            return $this->errorResponse(__('Server Error').': '.$e->getMessage(), 500);
        }
    }

    public function reviewReservation(ReservationReviewRequest $request)
    {
        try {
            $lang = $request->header('Accept-Language', 'en');
            if (str_contains(strtolower($lang), 'ar')) {
                app()->setLocale('ar');
            } else {
                app()->setLocale('en');
            }
            $language = app()->getLocale();
            $user = auth('sanctum')->user();

            $bookingCode = $request->input('booking_code');
            $hotelId = $request->input('hotel_id');
            $adults = (int) $request->input('adults', 1);
            $children = (int) $request->input('children', 0);
            $guests = $adults + $children;

            // Format PaxRooms for AvailabilityService
            $paxRooms = [
                [
                    'Adults' => $adults,
                    'Children' => $children,
                    'ChildrenAges' => array_fill(0, $children, 0), // Default to 0 for review step if not provided
                ],
            ];

            // 1. Validate Room Availability & Price (using AvailabilityService for normalization)
            $availability = $this->availabilityService->checkAvailability(
                $hotelId,
                $request->input('check_in'),
                $request->input('check_out'),
                $paxRooms,
                'SA'
            );

            if (! $availability->isAvailable()) {
                return $this->errorResponse(__('Room is no longer available. Please search again.'), 400);
            }

            // Find the room with matching booking_code
            $roomResult = null;
            foreach ($availability->rooms as $room) {
                if (isset($room['BookingCode']) && $room['BookingCode'] === $bookingCode) {
                    $roomResult = $room;
                    break;
                }
            }

            // Fallback: If not found in search results, verify directly via PreBook
            if (! $roomResult) {
                try {
                    $preBookResponse = $this->hotelApi->preBook($bookingCode);
                    if (isset($preBookResponse['Status']['Code']) && $preBookResponse['Status']['Code'] == 200) {
                        $roomResult = $preBookResponse['HotelResult'][0]['Rooms'][0] ?? null;
                        if (isset($preBookResponse['BookingCode'])) {
                            $bookingCode = $preBookResponse['BookingCode'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Review fallback PreBook failed: '.$e->getMessage());
                }
            }

            if (! $roomResult) {
                return $this->errorResponse(__('Room details could not be verified. Please search again.'), 400);
            }

            // 2. Fetch Hotel Details for display (Image, Name, Address)
            // ... (rest of the logic remains the same)
            $hotelDetails = Cache::remember("hotel_details_{$hotelId}_{$lang}", 86400, function () use ($hotelId, $lang) {
                $details = $this->hotelApi->getHotelDetails($hotelId, $lang);

                return $details['HotelDetails'] ?? null;
            });

            if (! $hotelDetails) {
                return $this->errorResponse(__('Failed to load hotel details.'), 500);
            }

            // 3. Calculate Prices & Comission
            $targetCurrency = \App\Helpers\CurrencyHelper::getCurrentCurrency();
            $finalPriceInUsd = (float) ($roomResult['TotalFare'] ?? 0);
            $finalPrice = \App\Helpers\CurrencyHelper::convert($finalPriceInUsd, $targetCurrency);
            $currency = $targetCurrency;

            // No need to apply commission again here as AvailabilityService does it
            // but we might want to know the base price for the response breakdown?
            // Actually, let's just use the final price for now as the user asked for simplicity.
            // If we want breakdown: Base = Final / (1 + (Percentage / 100))
            $commissionPercentage = \App\Helpers\CommissionHelper::getCommissionPercentage();
            $basePrice = $finalPrice / (1 + ($commissionPercentage / 100));

            // 4. Handle Coupon / Discount
            $discountCode = $request->input('discount_code');
            $discountAmount = 0;
            $couponStatus = null;
            $couponMessage = null;

            if ($discountCode) {
                $coupon = \App\Models\DiscountCode::where('code', $discountCode)->first();

                if ($coupon && $coupon->isValid()) {
                    // Calculate discount
                    // Assuming percentage discount based on model
                    $discountPercent = $coupon->discount_percentage; // e.g. 10.0
                    $discountAmount = ($finalPrice * $discountPercent) / 100;

                    // Cap discount if needed? Model doesn't have cap, so we trust percentage.
                    $couponStatus = 'valid';
                    $couponMessage = __('Coupon applied successfully.');
                } else {
                    $couponStatus = 'invalid';
                    $couponMessage = __('Invalid or expired coupon code.');
                }
            }

            $priceAfterDiscount = $finalPrice - $discountAmount;

            // 5. Prepare Guest Data
            // If user is logged in, use their data if not provided in request
            $guestData = [
                'name' => $request->input('name') ?? ($user ? $user->name : ''),
                'email' => $request->input('email') ?? ($user ? $user->email : ''),
                'phone' => $request->input('phone') ?? ($user ? $user->phone : ''),
                'phone_country_code' => $request->input('phone_country_code') ?? ($user ? $user->phone_country_code : ''),
                'notes' => $request->input('notes', ''),
            ];

            // 6. Formatting Response
            $response = [
                'hotel' => [
                    'id' => $hotelId,
                    'name' => $hotelDetails['HotelName'] ?? '',
                    'address' => $hotelDetails['Address'] ?? '',
                    'rating' => $hotelDetails['HotelRating'] ?? '0',
                    'image' => isset($hotelDetails['Images'][0]) ? $hotelDetails['Images'][0] : 'https://abrajstay.com/images/default.jpg',
                ],
                'room' => [
                    'booking_code' => $bookingCode,
                    'name' => $roomResult['Name'][0] ?? $roomResult['Name'] ?? ($lang === 'ar' ? 'غرفة' : 'Room'),
                    'inclusion' => $roomResult['Inclusion'] ?? '',
                    'check_in' => $request->input('check_in'),
                    'check_out' => $request->input('check_out'),
                    'rooms_count' => $request->input('rooms'),
                    'adults_count' => $adults,
                    'children_count' => $children,
                    'total_guests' => $guests,
                ],
                'pricing' => [
                    'currency' => $currency,
                    'is_agent_price' => false, // For now false, can be dynamic later
                    'base_price' => round($finalPrice, 2), // Price including commission (Before Discount)
                    'discount_amount' => round($discountAmount, 2),
                    'total_price' => round($priceAfterDiscount, 2), // Final User Pay
                    'tax_info' => $roomResult['Price']['GST'] ?? [], // Pass through tax info if available
                ],
                'coupon' => [
                    'code' => $discountCode,
                    'status' => $couponStatus,
                    'message' => $couponMessage,
                ],
                'guest' => $guestData,
                'cancellation_policies' => $roomResult['CancellationPolicies'] ?? [],
                'terms_conditions' => $roomResult['TermsAndConditions'] ?? $roomResult['Conditions'] ?? [],
            ];

            return $this->successResponse($response);

        } catch (\Exception $e) {
            Log::error('Review Reservation Error: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return $this->errorResponse(__('An error occurred while reviewing the reservation.').' '.$e->getMessage(), 500);
        }
    }

    /**
     * Handle payment callback from Amazon Payment Services (APS)
     * Mirrors web callback logic but returns JSON
     */
    public function paymentCallback(Request $request)
    {
        set_time_limit(180);
        $data = $request->all();

        // Log::info('API APS Callback Received', ['data' => $data]);

        // 1. Verify Signature
        $receivedSignature = $data['signature'] ?? null;
        $tempData = $data;
        unset($tempData['signature']);

        $generatedSignature = $this->paymentService->apsSignature($tempData, config('services.aps.sha_response'));

        if ($receivedSignature !== $generatedSignature) {
            Log::error('API APS Callback Signature Mismatch', [
                'received' => $receivedSignature,
                'generated' => $generatedSignature,
            ]);

            return $this->errorResponse('Invalid signature — payment not trusted', 403);
        }

        $merchantReference = $data['merchant_reference'] ?? '';
        $status = $data['status'] ?? '';

        // 2. Handle Hotel Bookings
        if (str_starts_with(strtoupper($merchantReference), 'BK-')) {
            $booking = \App\Models\HotelBooking::where('booking_reference', $merchantReference)->first();

            if (! $booking) {
                Log::error('Booking not found for Reference: '.$merchantReference);

                return $this->errorResponse('Booking not found', 404);
            }

            if ($status == '14') { // Success
                $success = $this->bookingService->completeBooking($booking, $data);

                if ($success) {
                    return $this->successResponse([
                        'booking_reference' => $booking->booking_reference,
                        'tbo_booking_id' => $booking->tbo_booking_id,
                        'confirmation_number' => $booking->confirmation_number,
                    ], __('Payment successful! Your booking has been confirmed.'));
                } else {
                    return $this->errorResponse(__('Payment successful, but room booking failed. Our team will contact you for a refund.'), 500);
                }
            } else { // Failure
                $this->bookingService->cancelBooking($booking, $data['response_message'] ?? 'Payment failed');

                return $this->errorResponse(__('Payment failed: ').($data['response_message'] ?? 'Unknown error'), 400);
            }
        }

        return $this->errorResponse('Unsupported payment reference', 400);
    }

    /**
     * Final step: Create booking and initiate payment
     */
    public function bookReservation(HotelBookingRequest $request)
    {
        set_time_limit(180);

        // 1. Localization (Header ONLY)
        $lang = $request->header('Accept-Language');
        if ($lang && str_contains(strtolower($lang), 'ar')) {
            app()->setLocale('ar');
        } else {
            app()->setLocale('en'); // Default
        }
        $language = app()->getLocale();

        try {
            $validated = $request->validated();
            $bookingCode = $validated['booking_code'];
            $hotelId = $validated['hotel_id'];

            // 2. Server-Side Price Calculation (Using PreBook for absolute freshness)
            // We call PreBook directly to get the final price from TBO
            $preBookResponse = $this->hotelApi->preBook($bookingCode);

            if (
                ! isset($preBookResponse['Status']['Code']) ||
                $preBookResponse['Status']['Code'] !== 200 ||
                ! isset($preBookResponse['HotelResult'][0]['Rooms'][0])
            ) {
                $msg = $preBookResponse['Status']['Description'] ?? __('Room is no longer available. Please search again.');

                return $this->errorResponse($msg, 400);
            }

            $roomResult = $preBookResponse['HotelResult'][0]['Rooms'][0];

            // TBO's preBook price can be in several places
            $basePrice = 0;
            if (isset($roomResult['TotalFare'])) {
                $basePrice = (float) $roomResult['TotalFare'];
            } elseif (isset($roomResult['Price']['TotalDisplayFare'])) {
                $basePrice = (float) $roomResult['Price']['TotalDisplayFare'];
            } elseif (isset($roomResult['Price']['OfferedPrice'])) {
                $basePrice = (float) $roomResult['Price']['OfferedPrice'];
            } elseif (isset($preBookResponse['TotalFare'])) {
                $basePrice = (float) $preBookResponse['TotalFare'];
            }

            if ($basePrice <= 0) {
                return $this->errorResponse(__('Room price could not be verified. Please try again.'), 400);
            }

            $currency = $roomResult['Price']['Currency'] ?? $roomResult['Currency'] ?? $preBookResponse['Currency'] ?? 'USD';

            // Apply Commission
            $finalPrice = \App\Helpers\CommissionHelper::applyCommission($basePrice);

            // Apply Currency Conversion
            $targetCurrency = \App\Helpers\CurrencyHelper::getCurrentCurrency();
            $finalPrice = \App\Helpers\CurrencyHelper::convert($finalPrice, $targetCurrency);
            $currency = $targetCurrency;

            // Handle Discount
            $discountAmount = 0;
            $discountCodeId = null;
            if (! empty($validated['discount_code'])) {
                $coupon = \App\Models\DiscountCode::where('code', $validated['discount_code'])->first();
                if ($coupon && $coupon->isValid()) {
                    $discountPercent = $coupon->discount_percentage;
                    $discountAmount = ($finalPrice * $discountPercent) / 100;
                    $discountCodeId = $coupon->id;
                }
            }

            $priceAfterDiscount = $finalPrice - $discountAmount;

            // 3. Fetch Localized Details for Enrichment
            $hotelDetails = Cache::remember("api_hotel_details_{$hotelId}_{$language}", 86400, fn () => $this->hotelApi->getHotelDetails($hotelId, $language)['HotelDetails'] ?? null);

            $hotelName = $hotelDetails['HotelName'] ?? $hotelDetails['Name'] ?? ($language === 'ar' ? 'فندق' : 'Hotel');

            // For DB storage we still might want both names or just use the current localized one as both for consistency in old fields
            $hotelNameAr = ($language === 'ar') ? $hotelName : (Cache::remember("hotel_details_{$hotelId}_ar", 86400, fn () => $this->hotelApi->getHotelDetails($hotelId, 'ar')['HotelDetails'] ?? null)['HotelName'] ?? $hotelName);
            $hotelNameEn = ($language === 'en') ? $hotelName : (Cache::remember("hotel_details_{$hotelId}_en", 86400, fn () => $this->hotelApi->getHotelDetails($hotelId, 'en')['HotelDetails'] ?? null)['HotelName'] ?? $hotelName);

            // Room name
            $roomName = $roomResult['Name'][0] ?? $roomResult['Name'] ?? ($language === 'ar' ? 'غرفة' : 'Room');

            // Calculate Nights
            $checkInDate = \Carbon\Carbon::parse($validated['check_in']);
            $checkOutDate = \Carbon\Carbon::parse($validated['check_out']);
            $nights = $checkInDate->diffInDays($checkOutDate);

            // Update validated data with SERVER-CALCULATED values
            $validated['room_code'] = $bookingCode;
            $validated['hotel_code'] = $hotelId;
            $validated['hotel_name_ar'] = $hotelNameAr;
            $validated['hotel_name_en'] = $hotelNameEn;
            $validated['total_price'] = round($priceAfterDiscount, 2);
            $validated['original_price'] = round($finalPrice, 2); // Price before discount
            $validated['currency'] = $currency;
            $validated['discount_amount'] = round($discountAmount, 2);
            $validated['discount_code_id'] = $discountCodeId;

            // Map guest fields
            $validated['guest_name'] = $validated['name'] ?? '';
            $validated['guest_email'] = $validated['email'] ?? '';
            $validated['guest_phone'] = $validated['phone'] ?? '';

            // 4. Initiate Booking
            $booking = $this->bookingService->initiateBooking($validated);

            // 5. Generate Payment Data for APS
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $booking->total_price,
                'currency' => $booking->currency,
                'customer_email' => $booking->guest_email,
                'merchant_reference' => $booking->booking_reference,
            ]);

            return $this->successResponse([
                'booking_reference' => $booking->booking_reference,
                'hotel' => [
                    'name' => $hotelName,
                ],
                'room' => [
                    'name' => $roomName,
                ],
                'reservation' => [
                    'check_in' => $validated['check_in'],
                    'check_out' => $validated['check_out'],
                    'nights_count' => $nights,
                ],
                'guest' => [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'phone_country_code' => $validated['phone_country_code'] ?? '',
                ],
                'pricing' => [
                    'total_price' => $booking->total_price,
                    'currency' => $booking->currency,
                ],
                'payment_data' => $paymentData,
                'payment_url' => config('services.aps.payment_url'),
                'return_url' => route('aps.callback'),
            ], __('Booking initiated successfully.'));

        } catch (\Exception $e) {
            Log::error('API Hotel Booking Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $message = $e->getMessage();
            $status = 500;

            if (str_contains($message, 'availability check failed') || str_contains($message, 'Room is no longer available')) {
                $status = 400;
            }

            return $this->errorResponse($message, $status);
        }
    }

    private function normalizeFacilitiesList($hotel)
    {
        $raw = $hotel['HotelFacilities'] ?? $hotel['Facilities'] ?? [];
        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }
        $rawStr = is_array($raw) ? implode(' ', array_map(function ($f) {
            return is_array($f) ? ($f['Name'] ?? '') : $f;
        }, $raw)) : '';

        $rawLower = mb_strtolower($rawStr);
        $result = [];
        $isAr = app()->getLocale() === 'ar';

        // Definitions
        $defs = [
            'wifi' => ['kw' => ['wifi', 'internet'], 'val' => $isAr ? 'واي فاي مجاني' : 'Free Wifi'],
            'pool' => ['kw' => ['pool', 'swim'], 'val' => $isAr ? 'مسبح' : 'Pool'],
            'restaurant' => ['kw' => ['restaurant', 'dining'], 'val' => $isAr ? 'مطعم' : 'Restaurant'],
            'spa' => ['kw' => ['spa', 'sauna', 'massage'], 'val' => $isAr ? 'سبا' : 'Spa'],
            'gym' => ['kw' => ['gym', 'fitness'], 'val' => $isAr ? 'جيم' : 'Gym'],
            'parking' => ['kw' => ['park', 'valet'], 'val' => $isAr ? 'موقف سيارات' : 'Parking'],
        ];

        foreach ($defs as $key => $def) {
            foreach ($def['kw'] as $k) {
                if (str_contains($rawLower, $k)) {
                    $result[] = $def['val'];
                    break;
                }
            }
        }

        return $result;
    }

    private function getHotelRating($rating)
    {
        $ratingArray = [
            'All' => '0',
            'OneStar' => '1',
            'TwoStar' => '2',
            'ThreeStar' => '3',
            'FourStar' => '4',
            'FiveStar' => '5',
        ];

        return $ratingArray[$rating];
    }
}
