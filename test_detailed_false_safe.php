<?php

use App\Services\Api\V1\HotelApiService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cityCode = '100639';
$api = new HotelApiService();

$response = $api->getHotels($cityCode, 1, 'en');
$hotels = $response['Hotels'] ?? [];
$first = $hotels[0] ?? [];

file_put_contents('detailed_false_output.txt', print_r(array_keys($first), true));
