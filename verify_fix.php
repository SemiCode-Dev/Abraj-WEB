<?php

use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use Illuminate\Support\Facades\Cache;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cityCode = '100639';
$cityParam = City::where('code', $cityCode)->first();
$cityNames = [];
if ($cityParam) {
    if ($cityParam->name_en) $cityNames[] = strtolower($cityParam->name_en);
    if ($cityParam->name_ar) $cityNames[] = strtolower($cityParam->name_ar);
    foreach ($cityNames as $name) {
        if (str_contains($name, ',')) {
            $parts = explode(',', $name);
            $cityNames[] = trim($parts[0]);
        }
    }
}

echo "Strict City Names: " . implode(', ', $cityNames) . "\n";

$api = new HotelApiService();
// Fetch lightweight list
$response = $api->getAllCityHotels($cityCode, false, 'en');
$allHotels = $response['Hotels'] ?? [];

echo "Total Raw Hotels: " . count($allHotels) . "\n";

$filtered = [];
foreach ($allHotels as $hotel) {
    $hotelCityName = strtolower($hotel['CityName'] ?? '');
    $hotelAddress = strtolower($hotel['Address'] ?? '');
    
    $match = false;
    foreach ($cityNames as $validName) {
        if (str_contains($hotelCityName, $validName)) {
            $match = true;
            break;
        }
        if (str_contains($hotelAddress, $validName)) {
            $match = true;
            break;
        }
    }
    
    if ($match) {
        $filtered[] = $hotel;
    }
}

echo "Filtered Hotels: " . count($filtered) . "\n";

if (!empty($filtered)) {
    echo "Sample Filtered:\n";
    $sample = array_slice($filtered, 0, 3);
    foreach ($sample as $h) {
        echo " - " . ($h['HotelName'] ?? $h['Name']) . " (" . ($h['CityName'] ?? 'N/A') . ")\n";
    }
}
