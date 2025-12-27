<?php

use App\Http\Controllers\Web\V1\LocationController;
use App\Services\Api\V1\HotelApiService;
use App\Services\HotelTranslationService; // Assuming this is the class name
use App\Models\Country;
use Illuminate\Support\Facades\App;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    echo "Benchmarking City Loading (Bahrain)...\n";
    
    // Set Locale to Arabic to trigger translation logic
    App::setLocale('ar');
    echo "Locale set to: " . App::getLocale() . "\n";

    $country = Country::where('name', 'like', '%Bahrain%')->orWhere('name_ar', 'like', '%البحرين%')->first();
    if (!$country) {
        die("Bahrain not found.\n");
    }

    $apiService = new HotelApiService();
    $translationService = new HotelTranslationService();
    $controller = new LocationController($apiService, $translationService);

    // 1. Measure Total Time (uncached if possible, but cache is cleared manually before)
    // We simulated a 'fresh' request by using a unique cache key or clearing cache
    \Cache::forget("cities_country_{$country->id}_ar_v2");

    $start = microtime(true);
    $response = $controller->getCities($country->id);
    $end = microtime(true);
    
    $duration = $end - $start;
    echo "Total Request Duration: " . number_format($duration, 4) . " seconds\n";

    $data = $response->getData();
    echo "Cities returned: " . count($data) . "\n";
    if (count($data) > 0) {
        echo "Sample City: " . json_encode($data[0], JSON_UNESCAPED_UNICODE) . "\n";
    }

    // 2. Component Benchmark
    echo "\n--- Component Benchmark ---\n";
    
    // TBO API
    $startApi = microtime(true);
    $apiResponse = $apiService->getCitiesByCountry($country->code, 'ar');
    $endApi = microtime(true);
    echo "TBO API Call ('ar'): " . number_format($endApi - $startApi, 4) . " seconds\n";
    
    $apiCities = $apiResponse['CityList'] ?? ($apiResponse[0] ?? []);
    echo "API Cities Count: " . count($apiCities) . "\n";
    if (!empty($apiCities)) {
        echo "API Sample (Raw): " . json_encode($apiCities[0], JSON_UNESCAPED_UNICODE) . "\n";
    }

    // Translation (Mocking what Controller does)
    if (!empty($apiCities)) {
        $citiesToTranslate = array_map(function ($city) {
            return [
                'HotelCode' => $city['Code'] ?? $city['CityCode'] ?? null,
                'Name' => $city['Name'] ?? $city['CityName'] ?? '',
                'OriginalCity' => $city
            ];
        }, $apiCities);

        $startTrans = microtime(true);
        // Translate ALL
        $limit = count($citiesToTranslate);
        echo "Translating $limit cities...\n";
        $translated = $translationService->translateHotels($citiesToTranslate, $limit);
        $endTrans = microtime(true);
        
        echo "Translation Service Duration: " . number_format($endTrans - $startTrans, 4) . " seconds\n";
        echo "Avg per city: " . ($limit > 0 ? number_format(($endTrans - $startTrans) / $limit, 4) : 0) . " s\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
