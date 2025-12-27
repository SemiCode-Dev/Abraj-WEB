<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Get cities for a specific country with caching.
     *
     * @param int|string $countryId
     * @return JsonResponse
     */
    public function __construct(
        protected \App\Services\Api\V1\HotelApiService $hotelApiService,
        protected \App\Services\HotelTranslationService $translationService
    ) {
    }

    /**
     * Get cities for a specific country with caching.
     *
     * @param int|string $countryId
     * @return JsonResponse
     */
    public function getCities($country): JsonResponse
    {
        try {
            // Cache key based on country ID and locale
            $locale = app()->getLocale();
            $cacheKey = "cities_country_{$country}_{$locale}_v2";
            
            // Cache for 24 hours
            $cities = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($country, $locale) {
                // Determine the name column based on locale
                $nameColumn = $locale == 'ar' ? 'name_ar' : 'name';
                
                // 1. Try fetching from Local Database
                $localCities = \App\Models\City::where('country_id', $country)
                    ->select('id', 'name', 'name_ar', 'code')
                    ->orderBy($nameColumn)
                    ->get();
                    
                if ($localCities->isNotEmpty()) {
                    return $localCities->map(function ($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->locale_name,
                            'code' => $city->code,
                        ];
                    });
                }

                // 2. Fallback to TBO API if local DB is empty
                $countryModel = Country::find($country);
                if (!$countryModel || empty($countryModel->code)) {
                    return [];
                }

                try {
                    $apiLanguage = $locale === 'ar' ? 'ar' : 'en';
                    $response = $this->hotelApiService->getCitiesByCountry($countryModel->code, $apiLanguage);
                    
                    $apiCities = [];
                    if (isset($response['CityList']) && is_array($response['CityList'])) {
                        $apiCities = $response['CityList'];
                    } elseif (is_array($response) && isset($response[0])) {
                        $apiCities = $response;
                    }

                    // Translate cities if needed
                    if ($locale === 'ar') {
                        // Transform to format expected by translateHotels (array with Name)
                        $citiesToTranslate = array_map(function ($city) {
                            return [
                                'HotelCode' => $city['Code'] ?? $city['CityCode'] ?? null, // Use CityCode as key
                                'Name' => $city['Name'] ?? $city['CityName'] ?? '',
                                'OriginalCity' => $city // Keep original data
                            ];
                        }, $apiCities);

                        // Use the existing service (it works for anything with Code and Name)
                        // Pass count as 2nd argument to allow translating ALL cities in this batch
                        $translated = $this->translationService->translateHotels($citiesToTranslate, count($citiesToTranslate));

                        // Map back to our structure
                        return collect($translated)->map(function ($item) {
                            return [
                                'id' => $item['HotelCode'], // City Code
                                'name' => $item['Name'],    // Translated Name
                                'code' => $item['HotelCode'],
                            ];
                        })->filter(function ($city) {
                            return !empty($city['id']);
                        })->sortBy('name')->values();
                    }

                    return collect($apiCities)->map(function ($city) {
                        return [
                            'id' => $city['Code'] ?? $city['CityCode'] ?? '', 
                            'name' => $city['Name'] ?? $city['CityName'] ?? '',
                            'code' => $city['Code'] ?? $city['CityCode'] ?? '',
                        ];
                    })->filter(function ($city) {
                        return !empty($city['id']); // Ensure we have an ID/Code
                    })->sortBy('name')->values();

                } catch (\Exception $e) {
                    Log::warning("Failed to fetch cities from TBO API for country {$country}: " . $e->getMessage());
                    return [];
                }
            });

            return response()->json($cities)
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours

        } catch (\Exception $e) {
            Log::error('Failed to fetch cities: ' . $e->getMessage());
            return response()->json(['error' => __('Failed to fetch cities')], 500);
        }
    }
}
