<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected HotelApiService $hotelApi;

    public function __construct(HotelApiService $hotelApi)
    {
        $this->hotelApi = $hotelApi;
    }

    public function index(Request $request)
    {
        // 1. Localization
        $lang = $request->header('Accept-Language');
        $language = ($lang && str_contains(strtolower($lang), 'ar')) ? 'ar' : 'en';
        app()->setLocale($language);

        // 2. Featured Destinations (Hardcoded to match Web design)
        $featuredDestinations = collect([
            [
                'id' => 1,
                'name' => $language === 'ar' ? 'الجزائر' : 'Algeria',
                'image' => asset('images/destinations/algeria.jpg'),
                'code' => 'ALG',
                'country_code' => 'DZ',
                'hotels_count' => 150,
                'trending' => true,
            ],
            [
                'id' => 2,
                'name' => $language === 'ar' ? 'إسكوشينتا' : 'Escuintla',
                'image' => asset('images/destinations/escuintla.jpg'),
                'code' => 'ESC',
                'country_code' => 'GT',
                'hotels_count' => 80,
                'trending' => true,
            ],
            [
                'id' => 3,
                'name' => $language === 'ar' ? 'دبي' : 'Dubai',
                'image' => asset('images/destinations/dubai.jpg'),
                'code' => 'DXB',
                'country_code' => 'AE',
                'hotels_count' => 500,
                'trending' => true,
            ],
            [
                'id' => 4,
                'name' => $language === 'ar' ? 'إسبيجولا' : 'Espejuela',
                'image' => asset('images/destinations/espejuela.jpg'),
                'code' => 'ESP',
                'country_code' => 'ES',
                'hotels_count' => 120,
                'trending' => true,
            ],
            [
                'id' => 5,
                'name' => $language === 'ar' ? 'الرياض' : 'Riyadh',
                'image' => asset('images/destinations/riyadh.jpg'),
                'code' => 'RUH',
                'country_code' => 'SA',
                'hotels_count' => 300,
                'trending' => true,
            ],
        ]);

        // 3. Fetch Hotels for "Selected Offers" and "Featured Hotels"
        // Use EXACT same logic as Web Controller
        $majorCityNames = ['Riyadh', 'Makkah/Mecca', 'Madinah', 'Jeddah', 'Dammam', 'Al Khobar'];

        $cityCodes = City::whereIn('name', $majorCityNames)
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->limit(4)
            ->pluck('code')
            ->toArray();

        // If not enough major cities, get any cities with codes
        if (count($cityCodes) < 4) {
            $otherCodes = City::whereNotIn('name', $majorCityNames)
                ->whereNotNull('code')
                ->where('code', '!=', '')
                ->limit(6 - count($cityCodes))
                ->pluck('code')
                ->toArray();
            $cityCodes = array_merge($cityCodes, $otherCodes);
        }

        // If no cities in database, use empty array (will return empty hotels)
        if (empty($cityCodes)) {
            $cityCodes = [];
        }

        // Cache for 2 hours to improve performance (same as Web)
        $cacheKey = 'featured_hotels_homepage_'.$language.'_'.md5(implode(',', $cityCodes));

        $response = Cache::remember($cacheKey, 7200, function () use ($cityCodes, $language) {
            try {
                if (empty($cityCodes)) {
                    return [
                        'Status' => [
                            'Code' => 200,
                            'Description' => 'Success',
                        ],
                        'Hotels' => [],
                    ];
                } else {
                    // Get all hotels from each city for Featured Hotels section (limit per city, single page)
                    $response = $this->hotelApi->getHotelsFromMultipleCities($cityCodes, true, 20, $language, 1);

                    // Apply robust Hotel Name Translation if Arabic
                    if ($language === 'ar') {
                        try {
                            $translator = new \App\Services\HotelTranslationService;
                            if (isset($response['Hotels']) && is_array($response['Hotels'])) {
                                $response['Hotels'] = $translator->translateHotels($response['Hotels'], 20);
                            }
                        } catch (\Exception $e) {
                            // ignore translation errors
                        }
                    }

                    return $response;
                }
            } catch (\Exception $e) {
                Log::error('API Home Hotels Fetch Error: '.$e->getMessage());

                return [
                    'Status' => [
                        'Code' => 500,
                        'Description' => 'Error',
                    ],
                    'Hotels' => [],
                ];
            }
        });

        $hotelsData = $response['Hotels'] ?? [];

        if (! is_array($hotelsData)) {
            $hotelsData = json_decode(json_encode($hotelsData), true);
        }

        shuffle($hotelsData);

        // Limit for "Selected Offers" (Flash Deals) - 4 items
        $selectedOffers = array_slice($hotelsData, 0, 4);

        // The rest for "Featured Stays"
        $featuredStays = array_slice($hotelsData, 4);

        // 4. Apply Filters (if requested)
        // Filter Featured Stays by stars
        $starFilter = $request->input('stars'); // e.g. "5,4" or just "5"

        if ($starFilter && $starFilter !== 'all') {
            $stars = explode(',', $starFilter);
            $featuredStays = array_filter($featuredStays, function ($hotel) use ($stars) {
                // TBO returns 'HotelRating' or 'Rating' - normalize to integer
                $rating = $this->getHotelRating($hotel['HotelRating'] ?? $hotel['Rating'] ?? 0);

                return in_array($rating, array_map('intval', $stars));
            });
            // Re-index
            $featuredStays = array_values($featuredStays);
        }

        // ALWAYS limit Featured Stays to 3 items (same as Web)
        $featuredStays = array_slice($featuredStays, 0, 3);

        // 5. Format Featured Stays for Display
        $formattedFeaturedStays = array_map(function ($hotel) {
            // Extract image
            $hotelImage = null;
            if (isset($hotel['ImageUrls']) && is_array($hotel['ImageUrls']) && ! empty($hotel['ImageUrls'][0]['ImageUrl'])) {
                $hotelImage = $hotel['ImageUrls'][0]['ImageUrl'];
            } elseif (isset($hotel['Image']) && ! empty($hotel['Image'])) {
                $hotelImage = $hotel['Image'];
            } elseif (isset($hotel['Images']) && is_array($hotel['Images']) && ! empty($hotel['Images'][0])) {
                $hotelImage = is_array($hotel['Images'][0])
                    ? ($hotel['Images'][0]['ImageUrl'] ?? null)
                    : $hotel['Images'][0];
            }

            // Default image if none found
            if (! $hotelImage) {
                $hotelImage = asset('images/default.jpg');
            }

            // Get star rating
            $starRating = $this->getHotelRating($hotel['HotelRating'] ?? $hotel['Rating'] ?? 0);

            return [
                'hotel_code' => $hotel['HotelCode'] ?? $hotel['Code'] ?? '',
                'name' => $hotel['HotelName'] ?? $hotel['Name'] ?? '',
                'address' => $hotel['Address'] ?? '',
                'city' => $hotel['CityName'] ?? '',
                'country' => $hotel['CountryName'] ?? '',
                'image' => $hotelImage,
                'rating' => $starRating,
                'source' => $hotel['Source'] ?? 'tbo',
                'facilities' => $this->normalizeFacilities($hotel),
            ];
        }, $featuredStays);

        // Format Selected Offers with same structure
        $formattedSelectedOffers = array_map(function ($hotel) {
            // Extract image
            $hotelImage = null;
            if (isset($hotel['ImageUrls']) && is_array($hotel['ImageUrls']) && ! empty($hotel['ImageUrls'][0]['ImageUrl'])) {
                $hotelImage = $hotel['ImageUrls'][0]['ImageUrl'];
            } elseif (isset($hotel['Image']) && ! empty($hotel['Image'])) {
                $hotelImage = $hotel['Image'];
            } elseif (isset($hotel['Images']) && is_array($hotel['Images']) && ! empty($hotel['Images'][0])) {
                $hotelImage = is_array($hotel['Images'][0])
                    ? ($hotel['Images'][0]['ImageUrl'] ?? null)
                    : $hotel['Images'][0];
            }

            // Default image if none found
            if (! $hotelImage) {
                $hotelImage = asset('images/default.jpg');
            }

            // Get star rating
            $starRating = $this->getHotelRating($hotel['HotelRating'] ?? $hotel['Rating'] ?? 0);

            return [
                'hotel_code' => $hotel['HotelCode'] ?? $hotel['Code'] ?? '',
                'name' => $hotel['HotelName'] ?? $hotel['Name'] ?? '',
                'address' => $hotel['Address'] ?? '',
                'city' => $hotel['CityName'] ?? '',
                'country' => $hotel['CountryName'] ?? '',
                'image' => $hotelImage,
                'rating' => $starRating,
                'discount' => '60%', // Hardcoded discount badge for offers
                'source' => $hotel['Source'] ?? 'tbo',
                'facilities' => $this->normalizeFacilities($hotel),
            ];
        }, $selectedOffers);

        // 6. Testimonials (Hardcoded to match Web)
        $testimonials = [
            [
                'id' => 1,
                'name' => 'Sarah Ali',
                'avatar_letter' => 'س',
                'time_ago' => __('week ago'),
                'rating' => 5,
                'review' => __('Review 2 Text'),
                'verified' => true,
            ],
            [
                'id' => 2,
                'name' => 'Ahmed Mohamed',
                'avatar_letter' => 'أ',
                'time_ago' => __('2 days ago'),
                'rating' => 5,
                'review' => __('Great service and huge variety of hotels.'),
                'verified' => true,
            ],
        ];

        $sections = [
            [
                'type' => 'amazing_deals',
                'title' => $language === 'ar' ? 'عروض مذهلة' : 'Amazing Deals',
                'data' => $formattedSelectedOffers,
            ],
            [
                'type' => 'featured_destinations',
                'title' => $language === 'ar' ? 'وجهات مميزة' : 'Featured Destinations',
                'data' => $featuredDestinations->values()->toArray(),
            ],
            [
                'type' => 'hotels_inspired',
                'title' => $language === 'ar' ? 'فنادق تلهمك' : 'Hotels That Inspire You',
                'data' => $formattedFeaturedStays,
            ],
            [
                'type' => 'reviews',
                'title' => $language === 'ar' ? 'آراء العملاء' : 'Reviews',
                'data' => $testimonials,
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Homepage data fetched successfully',
            'data' => $sections,
            'filters_available' => [
                'stars' => ['all', 3, 4, 5],
            ],
        ]);
    }

    /**
     * Helper method to normalize hotel rating from TBO format
     */
    private function getHotelRating($rating): int
    {
        if (is_numeric($rating)) {
            return (int) $rating;
        }

        // Handle string formats like "FiveStar", "5 Star", etc.
        $ratingStr = strtolower((string) $rating);

        if (str_contains($ratingStr, 'five') || str_contains($ratingStr, '5')) {
            return 5;
        }
        if (str_contains($ratingStr, 'four') || str_contains($ratingStr, '4')) {
            return 4;
        }
        if (str_contains($ratingStr, 'three') || str_contains($ratingStr, '3')) {
            return 3;
        }
        if (str_contains($ratingStr, 'two') || str_contains($ratingStr, '2')) {
            return 2;
        }
        if (str_contains($ratingStr, 'one') || str_contains($ratingStr, '1')) {
            return 1;
        }

        return 0;
    }

    /**
     * Helper to normalize facilities for Home
     */
    private function normalizeFacilities($hotel): array
    {
        $raw = $hotel['HotelFacilities'] ?? $hotel['Facilities'] ?? [];
        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }

        $rawStr = '';
        if (is_array($raw)) {
            $rawStr = implode(' ', array_map(function ($f) {
                if (is_array($f)) {
                    return $f['Name'] ?? '';
                }

                return (string) $f;
            }, $raw));
        }

        $rawLower = mb_strtolower($rawStr);

        return [
            'wifi' => str_contains($rawLower, 'wifi') || str_contains($rawLower, 'internet'),
            'utensils' => str_contains($rawLower, 'restaurant') || str_contains($rawLower, 'dining') || str_contains($rawLower, 'breakfast') || str_contains($rawLower, 'food'),
            'swimming-pool' => str_contains($rawLower, 'pool') || str_contains($rawLower, 'swim'),
        ];
    }
}
