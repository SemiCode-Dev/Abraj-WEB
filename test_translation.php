<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Services\HotelTranslationService;
use Illuminate\Support\Facades\Log;

echo "Testing HotelTranslationService...\n";

try {
    $service = new HotelTranslationService();
    $roomNames = ['Standard Double', 'Room Only', 'Deluxe King'];
    
    echo "Original: " . implode(', ', $roomNames) . "\n";
    
    $translated = $service->translateStrings($roomNames, 'en', 'ar');
    
    echo "Translated:\n";
    print_r($translated);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
