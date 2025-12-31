<?php

use App\Services\Api\V1\HotelApiService;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$api = new HotelApiService();
// Use two known codes (e.g., from previous output or common ones)
// I'll use 100639 city codes if I can find them, but I don't have them handy.
// I'll fetch simple list first to get 2 codes.

$list = $api->getHotels('100639', 1, 'en');
$codes = [];
if (isset($list['Hotels'])) {
    foreach ($list['Hotels'] as $h) {
        if (count($codes) < 2) $codes[] = $h['HotelCode'] ?? $h['Code'];
    }
}

if (count($codes) < 2) {
    echo "Not enough hotels found to test.\n";
    exit;
}

$codeString = implode(',', $codes);
echo "Testing HotelDetails with: $codeString\n";

$details = $api->getHotelDetails($codeString, 'en');

$results = $details['HotelDetails'] ?? $details['HotelResult'] ?? []; // Adjust based on actual response key
// TBO HotelDetails usually returns 'HotelDetails' array if multiple? Or just one object?

echo "Response Status: " . ($details['Status']['Code'] ?? 'Unknown') . "\n";
echo "Count: " . (isset($details['HotelDetails']) ? count($details['HotelDetails']) : 'N/A') . "\n";

if (isset($details['HotelDetails']) && is_array($details['HotelDetails'])) {
    foreach ($details['HotelDetails'] as $d) {
        echo " - " . ($d['HotelName'] ?? $d['Name'] ?? 'NoName') . "\n";
    }
} else {
    print_r($details); // Dump simple structure
}
