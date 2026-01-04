<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Services\Api\V1\HotelApiService;
use App\Services\HotelTranslationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationService
{
    public function __construct(
        protected HotelApiService $hotelApiService,
        protected HotelTranslationService $translationService
    ) {
    }

    /**
     * Get cities for a specific country, syncing from API if necessary.
     *
     * @param int|string $country Identifier (ID or Code)
     * @return Collection
     */
    public function getCities($country)
    {
        try {
            $locale = app()->getLocale();
            // We use the country input strictly for caching key to match previous behavior
            // but we might want to normalize it.
            $cacheKey = "cities_country_{$country}_{$locale}_v10"; 
            
            // Cache for 24 hours
            return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($country, $locale) {
                return $this->syncAndFetchCities($country, $locale);
            });
        } catch (\Exception $e) {
            Log::error('LocationService: Failed to get cities: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Force sync cities for a country and return them.
     * Use this when you want to ensure DB is populated.
     */
    public function syncCitiesForCountry($country, $locale = null)
    {
        if (!$locale) $locale = app()->getLocale();
        return $this->syncAndFetchCities($country, $locale);
    }

    protected function syncAndFetchCities($country, $locale)
    {
        // 1. Resolve Country
        $countryModel = Country::where('id', $country)
                        ->orWhere('code', $country)
                        ->first();

        $apiCountryCode = $countryModel ? $countryModel->code : (is_string($country) ? strtoupper($country) : null);
        
        if (empty($apiCountryCode) || strlen($apiCountryCode) > 3) {
            return collect([]);
        }

        // 2. Fetch from API (complete list)
        $apiCitiesRaw = $this->fetchCitiesFromApi($apiCountryCode, 'en'); // Always fetch EN for consistency
        
        if (empty($apiCitiesRaw)) return collect([]);

        // Process API data
        $processedCities = collect($apiCitiesRaw)->map(function ($city) {
            $code = $city['Code'] ?? $city['CityCode'] ?? null;
            $name = $city['Name'] ?? $city['CityName'] ?? null;
            if (!$code || !$name) return null;
            $name = trim(explode(',', $name)[0]);
            return ['code' => (string)$code, 'name' => $name];
        })->filter()->unique('code')->values();

        // 3. Sync and Translate if Country Model exists
        if ($countryModel) {
            $this->syncCitiesToDatabase($countryModel, $processedCities, $locale);
            
            // 4. Return Final List from DB (Deduplicated by Name for UI)
            $finalCities = City::where('country_id', $countryModel->id)
                ->select('id', 'name', 'name_ar', 'code')
                ->get();
            
            return $finalCities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->locale_name,
                    'code' => $city->code,
                ];
            })->sortBy('name')->unique('name')->values();
        }

        return $processedCities->unique('name')->values();
    }

    protected function fetchCitiesFromApi($countryCode, $lang)
    {
        try {
            $response = $this->hotelApiService->getCitiesByCountry($countryCode, $lang);
            return (isset($response['CityList']) ? $response['CityList'] : (is_array($response) && isset($response[0]) ? $response : []));
        } catch (\Exception $e) {
            Log::error("LocationService: API fetch failed for $countryCode: " . $e->getMessage());
            return [];
        }
    }

    protected function syncCitiesToDatabase($countryModel, $processedCities, $locale)
    {
        $dbCities = City::where('country_id', $countryModel->id)->get()->keyBy('code');

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
                    City::upsert($upsertData, ['code', 'country_id'], ['name', 'name_ar', 'updated_at']);
                }
            }
        } else {
            // Standard Sync (English/Other)
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
                 City::upsert($upsertData, ['code', 'country_id'], ['name', 'updated_at']);
            }
        }
    }
}
