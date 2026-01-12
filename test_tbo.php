<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\Api\V1\HotelApiService;

$api = new HotelApiService();
echo "Testing TBO API for Riyadh (100685)...\n";
$res = $api->getCityHotels('100685', 1, 'en');

echo "Status: " . ($res['Status']['Code'] ?? 'NULL') . "\n";
echo "Description: " . ($res['Status']['Description'] ?? 'NULL') . "\n";
echo "Hotels Count: " . (isset($res['Hotels']) ? count($res['Hotels']) : '0') . "\n";

if (isset($res['Hotels']) && count($res['Hotels']) > 0) {
    echo "First Hotel: " . ($res['Hotels'][0]['HotelName'] ?? $res['Hotels'][0]['Name'] ?? 'Unknown Name') . "\n";
} else {
    echo "Raw Response: " . json_encode($res) . "\n";
}
