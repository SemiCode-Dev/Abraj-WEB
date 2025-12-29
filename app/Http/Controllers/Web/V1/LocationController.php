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
            $cacheKey = "cities_country_{$country}_{$locale}_v10"; // Bumped to v10
            
            // Cache for 24 hours
            $cities = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($country, $locale) {
                // 1. Resolve Country
                $countryModel = Country::where('id', $country)
                                ->orWhere('code', $country)
                                ->first();

                $apiCountryCode = $countryModel ? $countryModel->code : (is_string($country) ? strtoupper($country) : null);
                if (empty($apiCountryCode) || strlen($apiCountryCode) > 3) {
                    return [];
                }

                // 2. Fetch from API (complete list)
                $fetchFromApi = function($lang) use ($apiCountryCode) {
                    try {
                        $response = $this->hotelApiService->getCitiesByCountry($apiCountryCode, $lang);
                        return (isset($response['CityList']) ? $response['CityList'] : (is_array($response) && isset($response[0]) ? $response : []));
                    } catch (\Exception $e) {
                        return [];
                    }
                };

                $apiCitiesRaw = $fetchFromApi('en');
                if (empty($apiCitiesRaw)) return [];

                $processedCities = collect($apiCitiesRaw)->map(function ($city) {
                    $code = $city['Code'] ?? $city['CityCode'] ?? null;
                    $name = $city['Name'] ?? $city['CityName'] ?? null;
                    if (!$code || !$name) return null;
                    $name = trim(explode(',', $name)[0]);
                    return ['code' => (string)$code, 'name' => $name];
                })->filter()->unique('code')->values();

                // 3. Sync and Translate
                if ($countryModel) {
                    $dbCities = \App\Models\City::where('country_id', $countryModel->id)->get()->keyBy('code');

                    // Translation logic for Arabic
                    if ($locale === 'ar') {
                        $toTranslate = $processedCities->filter(function($city) use ($dbCities) {
                            return !isset($dbCities[$city['code']]) || empty($dbCities[$city['code']]->name_ar);
                        });

                        if ($toTranslate->isNotEmpty()) {
                            // Unique names to minimize API calls
                            $uniqueNames = $toTranslate->pluck('name')->unique()->toArray();
                            $translationsList = $this->translationService->translateStrings($uniqueNames);

                            $timestamp = now();
                            $upsertData = $processedCities->map(function($city) use ($countryModel, $translationsList, $dbCities, $timestamp) {
                                $arName = $translationsList[$city['name']] ?? ($dbCities[$city['code']]->name_ar ?? null);
                                return [
                                    'country_id' => $countryModel->id,
                                    'name' => $city['name'],
                                    'name_ar' => $arName,
                                    'code' => $city['code'],
                                    'created_at' => $dbCities[$city['code']]->created_at ?? $timestamp,
                                    'updated_at' => $timestamp,
                                ];
                            })->toArray();

                            if (!empty($upsertData)) {
                                \App\Models\City::upsert($upsertData, ['code', 'country_id'], ['name', 'name_ar', 'updated_at']);
                            }
                        }
                    } else {
                        // Standard Sync (English)
                        $timestamp = now();
                        $upsertData = $processedCities->map(function($city) use ($countryModel, $dbCities, $timestamp) {
                            return [
                                'country_id' => $countryModel->id,
                                'name' => $city['name'],
                                'code' => $city['code'],
                                'created_at' => $dbCities[$city['code']]->created_at ?? $timestamp,
                                'updated_at' => $timestamp,
                            ];
                        })->toArray();
                        
                        if (!empty($upsertData)) {
                             \App\Models\City::upsert($upsertData, ['code', 'country_id'], ['name', 'updated_at']);
                        }
                    }
                }

                // 4. Return Final List from DB (Deduplicated by Name for UI)
                if ($countryModel) {
                    $finalCities = \App\Models\City::where('country_id', $countryModel->id)
                        ->select('id', 'name', 'name_ar', 'code')
                        ->get();
                    
                    return $finalCities->map(function ($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->locale_name,
                            'code' => $city->code,
                        ];
                    })->sortBy('name')->unique('name')->values(); // DEDUPLICATE BY NAME here
                }

                return $processedCities->unique('name')->values();
            });

            return response()->json($cities)
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours

        } catch (\Exception $e) {
            Log::error('Failed to fetch cities: ' . $e->getMessage());
            return response()->json(['error' => __('Failed to fetch cities')], 500);
        }
    }
}
