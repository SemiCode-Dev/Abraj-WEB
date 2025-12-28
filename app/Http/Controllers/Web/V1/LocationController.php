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
                $nameColumn = $locale == 'ar' ? 'name_ar' : 'name';
                
                // Resolve input to confirmed Country ID and Code
                $countryModel = Country::where('id', $country)
                                ->orWhere('code', $country)
                                ->first();

                // 1. Try fetching from Local Database
                if ($countryModel) {
                    $localCities = \App\Models\City::where('country_id', $countryModel->id)
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
                }

                // 2. Fallback to TBO API
                $apiCountryCode = $countryModel ? $countryModel->code : (is_string($country) ? strtoupper($country) : null);

                if (empty($apiCountryCode) || strlen($apiCountryCode) > 3) {
                    Log::warning("getCities: Invalid country code resolved: $apiCountryCode");
                    return [];
                }

                $fetchFromApi = function($lang) use ($apiCountryCode) {
                    try {
                        $response = $this->hotelApiService->getCitiesByCountry($apiCountryCode, $lang);
                        
                        if (isset($response['CityList']) && is_array($response['CityList'])) {
                            return $response['CityList'];
                        } elseif (is_array($response) && isset($response[0])) {
                            return $response;
                        }
                        return [];
                    } catch (\Exception $e) {
                        Log::error("getCities API exception for $apiCountryCode ($lang): " . $e->getMessage());
                        return [];
                    }
                };

                // Try current locale first
                $apiLanguage = $locale === 'ar' ? 'ar' : 'en';
                $apiCities = $fetchFromApi($apiLanguage);

                // If empty and we were trying Arabic, fallback to English
                if (empty($apiCities) && $apiLanguage === 'ar') {
                    $apiCities = $fetchFromApi('en');
                }

                if (empty($apiCities)) {
                    return [];
                }

                // Translate to Arabic if needed and not already translated by Provider
                if ($locale === 'ar') {
                    // Transform to format expected by translation service
                    $citiesToTranslate = array_map(function ($city) {
                        return [
                            'HotelCode' => $city['Code'] ?? $city['CityCode'] ?? null,
                            'Name' => $city['Name'] ?? $city['CityName'] ?? '',
                        ];
                    }, $apiCities);

                    // Use the service to translate names (it handles caching)
                    $translated = $this->translationService->translateHotels($citiesToTranslate, count($citiesToTranslate));

                    return collect($translated)->map(function ($item) {
                        return [
                            'id' => $item['HotelCode'],
                            'name' => $item['Name'],
                            'code' => $item['HotelCode'],
                        ];
                    })->filter(fn($c) => !empty($c['id']) && !empty($c['name']))
                      ->sortBy('name')->values();
                }

                return collect($apiCities)->map(function ($city) {
                    return [
                        'id' => $city['Code'] ?? $city['CityCode'] ?? '', 
                        'name' => $city['Name'] ?? $city['CityName'] ?? '',
                        'code' => $city['Code'] ?? $city['CityCode'] ?? '',
                    ];
                })->filter(fn($c) => !empty($c['id']) && !empty($c['name']))
                  ->sortBy('name')->values();
            });

            return response()->json($cities)
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours

        } catch (\Exception $e) {
            Log::error('Failed to fetch cities: ' . $e->getMessage());
            return response()->json(['error' => __('Failed to fetch cities')], 500);
        }
    }
}
