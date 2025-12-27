<?php

use App\Services\Api\V1\HotelApiService;
use App\Models\Country;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    $apiService = new HotelApiService();
    
    // Get Bahrain Code
    $country = Country::where('name', 'like', '%Bahrain%')->first();
    echo "Country: {$country->name} ({$country->code})\n";

    echo "Fetching cities in ARABIC...\n";
    $response = $apiService->getCitiesByCountry($country->code, 'ar');
    
    $cities = $response['CityList'] ?? ($response[0] ?? []);
    
    if (count($cities) > 0) {
        $first = $cities[0];
        $name = $first['Name'] ?? $first['CityName'] ?? 'Unknown';
        echo "Sample City Name: '{$name}'\n";
        
        // Check if contains Arabic
        if (preg_match('/\p{Arabic}/u', $name)) {
            echo "Result: TBO supports Arabic! (No translation needed)\n";
        } else {
            echo "Result: TBO returned English. (Translation REQUIRED)\n";
        }
    } else {
        echo "No cities returned.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
