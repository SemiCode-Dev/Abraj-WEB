<?php

namespace App\Services\Hotel;

use App\Constants\BookingStatus;
use App\Helpers\CommissionHelper;
use App\Models\HotelBooking;
use App\Services\Api\V1\HotelApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AvailabilityService
{
    private HotelApiService $hotelApi;

    public function __construct(HotelApiService $hotelApi)
    {
        $this->hotelApi = $hotelApi;
    }

    /**
     * Check availability for a single or multiple hotels
     *
     * @param  string|array  $hotelIds  Single ID or array of IDs
     * @param  string  $checkIn  Format: Y-m-d
     * @param  string  $checkOut  Format: Y-m-d
     * @param  array  $paxRooms  Array of rooms with Adults, Children, ChildrenAges
     * @param  string  $guestNationality  ISO country code
     */
    public function checkAvailability(
        string|array $hotelIds,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality = 'SA',
        bool $isDetailed = true
    ): AvailabilityResult {

        $hotelIdsStr = is_array($hotelIds) ? implode(',', $hotelIds) : $hotelIds;
        
        // Include $isDetailed AND locale in cache key to avoid mixing modes or languages
        $language = app()->getLocale();
        $detailSuffix = $isDetailed ? 'detailed' : 'standard';
        $cacheKey = $this->buildCacheKey($hotelIdsStr, $checkIn, $checkOut, $paxRooms, $guestNationality) . ":$detailSuffix:$language";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use (
            $hotelIdsStr, $checkIn, $checkOut, $paxRooms, $guestNationality, $isDetailed
        ) {
            return $this->fetchAvailability($hotelIdsStr, $checkIn, $checkOut, $paxRooms, $guestNationality, $isDetailed);
        });
    }

    /**
     * Search for all available hotels in a city
     *
     * @return array Array of AvailabilityResult keyed by hotel ID
     */
    public function checkCityAvailability(
        string $cityCode,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality = 'SA'
    ): array {
        $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
        
        // Cache by city and dates (short TTL as availability changes)
        $cacheKey = "city_avail_{$cityCode}_{$checkIn}_{$checkOut}_" . md5(json_encode($paxRooms));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($cityCode, $checkIn, $checkOut, $paxRooms, $guestNationality, $language) {
            try {
                $response = $this->hotelApi->searchHotel([
                    'CityCode' => $cityCode,
                    'CheckIn' => $checkIn,
                    'CheckOut' => $checkOut,
                    'GuestNationality' => $guestNationality,
                    'PaxRooms' => $paxRooms,
                    'IsDetailedResponse' => false, // Fast city search
                    'Language' => $language,
                    'Filters' => [
                        'Refundable' => false,
                        'NoOfRooms' => count($paxRooms),
                        'MealType' => 'All',
                    ],
                ]);

                if (!isset($response['Status']['Code']) || $response['Status']['Code'] != 200) {
                    Log::warning('AvailabilityService City - API Error', ['status' => $response['Status']['Code'] ?? 'unknown', 'city' => $cityCode]);
                    return [];
                }

                return $this->mapLightweightSearchResponse($response);
            } catch (\Exception $e) {
                Log::error('AvailabilityService City - Failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Map a lightweight TBO Search response (Hotels array) to AvailabilityResult objects
     */
    private function mapLightweightSearchResponse(array $response): array
    {
        $mappedResults = [];
        $hotelResults = $response['HotelResult'] ?? [];
        $hotels = $response['Hotels'] ?? [];
        
        $dataToMap = !empty($hotelResults) ? $hotelResults : $hotels;

        foreach ($dataToMap as $hotelData) {
            $hotelCode = (string)($hotelData['HotelCode'] ?? $hotelData['Code'] ?? '');
            if (!$hotelCode) continue;

            $currency = $hotelData['Currency'] ?? 'USD';
            $rooms = $hotelData['Rooms'] ?? [];
            
            // Check for rooms first if available
            if (!empty($rooms)) {
                [$minPrice, $commissionedRooms] = $this->processRoomsAndCalculateMinPrice($rooms);
                if ($minPrice > 0) {
                    $mappedResults[$hotelCode] = new AvailabilityResult('available', $minPrice, $currency, count($commissionedRooms), $commissionedRooms);
                    continue;
                }
            }

            // Fallback to top-level MinPrice
            $minPrice = (float)($hotelData['MinPrice'] ?? 0);
            if ($minPrice > 0) {
                $commissionedMinPrice = CommissionHelper::applyCommission($minPrice);
                $mappedResults[$hotelCode] = new AvailabilityResult('available', $commissionedMinPrice, $currency, 1, []);
            } else {
                $mappedResults[$hotelCode] = new AvailabilityResult('no_rooms', null, $currency, 0, []);
            }
        }

        return $mappedResults;
    }

    public function checkBatchAvailability(
        array $hotelIds,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality = 'SA',
        bool $isDetailed = true
    ): array {
        $results = [];
        $missingIds = [];
        $cacheKeys = [];

        // 1. Resolve as many as possible from cache
        foreach ($hotelIds as $hotelId) {
            // Include $isDetailed AND locale in cache key to avoid mixing modes or languages
            $language = app()->getLocale();
            $detailSuffix = $isDetailed ? 'detailed' : 'standard';
            $key = $this->buildCacheKey($hotelId, $checkIn, $checkOut, $paxRooms, $guestNationality) . ":$detailSuffix:$language";
            $cacheKeys[$hotelId] = $key;
            
            $cached = Cache::get($key);
            if ($cached instanceof AvailabilityResult) {
                $results[$hotelId] = $cached;
            } else {
                $missingIds[] = $hotelId;
            }
        }

        // 2. Fetch missing hotels in ONE BATCH call
        if (!empty($missingIds)) {
            Log::debug("AvailabilityService - Fetching batch from TBO", [
                "count" => count($missingIds),
                "mode" => $isDetailed ? 'detailed' : 'standard'
            ]);
            
            // Join IDs into comma-separated string for TBO
            $idsString = implode(',', $missingIds);
            $batchResults = $this->fetchBatchAvailability($idsString, $checkIn, $checkOut, $paxRooms, $guestNationality, $isDetailed);
            
            foreach ($missingIds as $hotelId) {
                $result = $batchResults[$hotelId] ?? new AvailabilityResult(
                    status: 'no_rooms',
                    minPrice: null,
                    currency: 'USD',
                    availableRoomsCount: 0,
                    rooms: []
                );
                
                // Cache individual result
                Cache::put($cacheKeys[$hotelId], $result, now()->addMinutes(30));
                $results[$hotelId] = $result;
            }
        }

        return $results;
    }

    /**
     * Fetch availability for multiple hotels in one API call
     */
    private function fetchBatchAvailability(
        string $hotelIds,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality,
        bool $isDetailed = true
    ): array {
        try {
            $language = app()->getLocale() === 'ar' ? 'ar' : 'en';

            $response = $this->hotelApi->searchHotel([
                'CheckIn' => $checkIn,
                'CheckOut' => $checkOut,
                'HotelCodes' => $hotelIds,
                'GuestNationality' => $guestNationality,
                'PaxRooms' => $paxRooms,
                'ResponseTime' => $isDetailed ? 28 : 20, // Increased for better stability (was 25/15)
                'IsDetailedResponse' => $isDetailed,
                'Language' => $language,
                'Filters' => [
                    'Refundable' => false,
                    'NoOfRooms' => count($paxRooms),
                    'MealType' => 'All',
                ],
            ]);

            if (!isset($response['Status']['Code']) || $response['Status']['Code'] != 200) {
                Log::warning('AvailabilityService Batch - API Error', [
                    'status' => $response['Status']['Code'] ?? 'unknown',
                    'codes_count' => count(explode(',', $hotelIds)),
                    'codes_sample' => substr($hotelIds, 0, 50) . '...'
                ]);
                return [];
            }

            $mappedResults = [];
            $hotelResults = $response['HotelResult'] ?? [];
            $hotels = $response['Hotels'] ?? [];

            if (!empty($hotelResults)) {
                // Handle HotelResult (standard when searching by codes)
                foreach ($hotelResults as $hotelData) {
                    $hotelCode = (string)$hotelData['HotelCode'];
                    $rooms = $hotelData['Rooms'] ?? [];
                    $currency = $hotelData['Currency'] ?? 'USD';

                    $filteredRooms = $this->filterLocallyBookedRooms($rooms, $hotelCode, $checkIn, $checkOut);
                    
                    if (empty($filteredRooms)) {
                        // FALLBACK: In some lightweight modes, TBO might return MinPrice at top level but no Rooms array
                        $topMinPrice = (float)($hotelData['MinPrice'] ?? 0);
                        if ($topMinPrice > 0) {
                            $commissionedMinPrice = CommissionHelper::applyCommission($topMinPrice);
                            $mappedResults[$hotelCode] = new AvailabilityResult('available', $commissionedMinPrice, $currency, 1, []);
                        } else {
                            $mappedResults[$hotelCode] = new AvailabilityResult('no_rooms', null, $currency, 0, []);
                        }
                        continue;
                    }

                    [$minPrice, $commissionedRooms] = $this->processRoomsAndCalculateMinPrice($filteredRooms);
                    
                    if ($minPrice > 0) {
                        $mappedResults[$hotelCode] = new AvailabilityResult('available', $minPrice, $currency, count($commissionedRooms), $commissionedRooms);
                    } else {
                        $mappedResults[$hotelCode] = new AvailabilityResult('no_rooms', null, $currency, 0, []);
                    }
                }
            } elseif (!empty($hotels)) {
                // Handle Hotels (standard for city search)
                foreach ($hotels as $hotelData) {
                    $hotelCode = $hotelData['HotelCode'];
                    $minPrice = (float)($hotelData['MinPrice'] ?? 0);
                    $currency = $hotelData['Currency'] ?? 'USD';

                    if ($minPrice > 0) {
                        $commissionedMinPrice = CommissionHelper::applyCommission($minPrice);
                        $mappedResults[$hotelCode] = new AvailabilityResult('available', $commissionedMinPrice, $currency, 1, []);
                    } else {
                        $mappedResults[$hotelCode] = new AvailabilityResult('no_rooms', null, $currency, 0, []);
                    }
                }
            }

            Log::debug("AvailabilityService Batch - Mapped results", [
                "found_in_response" => count($hotelResults) + count($hotels),
                "successfully_mapped" => count($mappedResults)
            ]);

            return $mappedResults;

        } catch (\Exception $e) {
            Log::error("AvailabilityService Batch Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch availability from TBO API (single-hotel wrapper)
     */
    private function fetchAvailability(
        string $hotelId,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality,
        bool $isDetailed = true
    ): AvailabilityResult {
        $results = $this->fetchBatchAvailability($hotelId, $checkIn, $checkOut, $paxRooms, $guestNationality, $isDetailed);
        
        return $results[$hotelId] ?? new AvailabilityResult(
            status: 'no_rooms',
            minPrice: null,
            currency: 'USD',
            availableRoomsCount: 0,
            rooms: []
        );
    }

    /**
     * Filter out locally booked rooms
     *
     * @param  array  $rooms  Rooms from TBO API
     * @return array Filtered rooms
     */
    private function filterLocallyBookedRooms(
        array $rooms,
        string $hotelId,
        string $checkIn,
        string $checkOut
    ): array {

        // Get local bookings for this hotel and date range
        $reservedRoomCounts = HotelBooking::where('hotel_code', $hotelId)
            ->whereIn('booking_status', [
                BookingStatus::CONFIRMED,
                BookingStatus::PENDING,
            ])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->selectRaw('room_name, COUNT(*) as reserved_count')
            ->groupBy('room_name')
            ->pluck('reserved_count', 'room_name')
            ->toArray();

        if (empty($reservedRoomCounts)) {
            // No local bookings, all rooms available
            return $rooms;
        }

        Log::debug('AvailabilityService - Local bookings found', [
            'hotel_id' => $hotelId,
            'reserved_rooms' => $reservedRoomCounts,
        ]);

        // Filter rooms
        $availableRooms = [];

        foreach ($rooms as $room) {
            $roomName = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');

            if (empty($roomName)) {
                continue; // Skip rooms without names
            }

            // TBO typically returns 1 instance per room type
            $tboCount = 1;

            // Check if fully booked locally
            $reservedCount = $reservedRoomCounts[$roomName] ?? 0;

            if ($reservedCount >= $tboCount) {
                Log::debug('AvailabilityService - Room filtered out', [
                    'hotel_id' => $hotelId,
                    'room_name' => $roomName,
                    'reserved' => $reservedCount,
                    'tbo_count' => $tboCount,
                ]);

                continue; // Skip fully booked room
            }

            $availableRooms[] = $room;
        }

        return $availableRooms;
    }

    /**
     * Calculate minimum price from rooms and apply commission to each room
     *
     * @param  array  $rooms  Original rooms from TBO
     * @return array [float $minPrice, array $commissionedRooms]
     */
    private function processRoomsAndCalculateMinPrice(array $rooms): array
    {
        $minPrice = null;
        $commissionedRooms = [];

        foreach ($rooms as &$room) {
            // Robust price extraction - check absolute price fields first
            $originalPrice = 0;
            if (isset($room['TotalFare'])) {
                $originalPrice = (float) $room['TotalFare'];
            } elseif (isset($room['Price']['OfferedPrice'])) {
                $originalPrice = (float) $room['Price']['OfferedPrice'];
            } elseif (isset($room['Price']['PublishedPrice'])) {
                $originalPrice = (float) $room['Price']['PublishedPrice'];
            } elseif (isset($room['Price']['Amount'])) {
                $originalPrice = (float) $room['Price']['Amount'];
            } elseif (isset($room['Rate']['Amount'])) {
                $originalPrice = (float) $room['Rate']['Amount'];
            }

            if ($originalPrice > 0) {
                // Apply commission to the room
                $roomPriceWithCommission = CommissionHelper::applyCommission($originalPrice);

                // CRITICAL: Synchronize ALL price fields for consistency across all views/JS
                $room['TotalFare'] = $roomPriceWithCommission;

                if (isset($room['Price'])) {
                    $room['Price']['OfferedPrice'] = $roomPriceWithCommission;
                    $room['Price']['PublishedPrice'] = $roomPriceWithCommission;
                    $room['Price']['Amount'] = $roomPriceWithCommission;
                } else {
                    $room['Price'] = [
                        'OfferedPrice' => $roomPriceWithCommission,
                        'PublishedPrice' => $roomPriceWithCommission,
                        'Amount' => $roomPriceWithCommission,
                    ];
                }

                if (isset($room['Rate'])) {
                    $room['Rate']['Amount'] = $roomPriceWithCommission;
                } else {
                    $room['Rate'] = ['Amount' => $roomPriceWithCommission];
                }

                // Track minimum price (after commission to ensure consistency)
                if ($minPrice === null || $roomPriceWithCommission < $minPrice) {
                    $minPrice = $roomPriceWithCommission;
                }
            }

            $commissionedRooms[] = $room;
        }
        unset($room); // Break reference

        return [$minPrice ?? 0, $commissionedRooms];
    }

    /**
     * Build cache key with full context
     */
    private function buildCacheKey(
        string $hotelId,
        string $checkIn,
        string $checkOut,
        array $paxRooms,
        string $guestNationality
    ): string {

        // Create hash of pax rooms to keep key short but unique
        $paxHash = md5(json_encode($paxRooms));

        return "hotel_availability:{$hotelId}:{$checkIn}:{$checkOut}:{$paxHash}:{$guestNationality}";
    }
}
