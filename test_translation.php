<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Services\HotelTranslationService;

$translator = new HotelTranslationService();

$hotels = [
    [
        'HotelCode' => 'TEST001',
        'HotelName' => 'Test Hotel',
        'Address' => 'King Fahd Road, Riyadh',
    ]
];

echo "Original: " . print_r($hotels, true) . "\n";

$translated = $translator->translateHotels($hotels, 1);

echo "Translated: " . print_r($translated, true) . "\n";
