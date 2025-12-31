<?php

use App\Models\City;
use App\Services\Api\V1\HotelApiService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cityCode = '100639';
$city = City::where('code', $cityCode)->first();

$data = [
    'db_city' => $city ? $city->toArray() : null,
    'api_hotels' => []
];

$api = new HotelApiService();
// Fetch just 1 page for speed
$response = $api->getAllCityHotels($cityCode, true, 'en'); 

$hotels = $response['Hotels'] ?? [];
$sampled = array_slice($hotels, 0, 10);

foreach ($sampled as $hotel) {
    $data['api_hotels'][] = [
        'Name' => $hotel['HotelName'] ?? $hotel['Name'] ?? 'N/A',
        'CityName' => $hotel['CityName'] ?? 'N/A',
        'Address' => $hotel['Address'] ?? 'N/A',
        'Code' => $hotel['HotelCode'] ?? $hotel['Code'] ?? 'N/A',
    ];
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
