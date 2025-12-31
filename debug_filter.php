<?php

use App\Models\City;
use App\Services\Api\V1\HotelApiService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cityCode = '100639';
$cityParam = City::where('code', $cityCode)->first();
// Dump DB names to see what we're working with
var_dump($cityParam->name_en);
var_dump($cityParam->name_ar);

$api = new HotelApiService();
$response = $api->getAllCityHotels($cityCode, false, 'en');
$allHotels = $response['Hotels'] ?? [];

echo "First 5 Hotels Raw Data:\n";
$i = 0;
foreach ($allHotels as $hotel) {
    if ($i++ >= 5) break;
    echo "CityName: [" . ($hotel['CityName'] ?? 'NULL') . "]\n";
    echo "Address: [" . ($hotel['Address'] ?? 'NULL') . "]\n";
    echo "-----------------\n";
}
