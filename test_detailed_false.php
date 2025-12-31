<?php

use App\Models\City;
use App\Services\Api\V1\HotelApiService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cityCode = '100639';
$api = new HotelApiService();

// Test IsDetailedResponse = false
echo "Fetching IsDetailedResponse=false...\n";
// Call getHotels directly which sets IsDetailedResponse=false
$response = $api->getHotels($cityCode, 1, 'en');

$hotels = $response['Hotels'] ?? [];
$first = $hotels[0] ?? null;

if ($first) {
    echo "Keys: " . implode(", ", array_keys($first)) . "\n";
    echo "Image? " . (isset($first['ImageUrls']) || isset($first['Images']) ? "Yes" : "No") . "\n";
    echo "Rating? " . (isset($first['HotelRating']) || isset($first['Rating']) ? "Yes" : "No") . "\n";
} else {
    echo "No hotels found.\n";
}
