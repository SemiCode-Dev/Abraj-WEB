<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Services\Api\V1\HotelApiService;
use App\Services\HotelTranslationService;
use Illuminate\Support\Facades\Log;

echo "Debugging Room Fetch and Translation...\n";

try {
    // Mimic the controller logic
    $hotelApi = app(HotelApiService::class);
    $translationService = new HotelTranslationService();
    
    $checkIn = '2026-01-01'; // Future date from screenshot
    $checkOut = '2026-01-15';
    $hotelId = '149191';
    
    $searchData = [
        'CheckIn' => $checkIn,
        'CheckOut' => $checkOut,
        'HotelCodes' => $hotelId,
        'GuestNationality' => 'AE',
        'PaxRooms' => [
            [
                'Adults' => 2,
                'Children' => 0,
                'ChildrenAges' => [],
            ],
        ],
        'ResponseTime' => 20,
        'IsDetailedResponse' => true,
        'Filters' => [
            'Refundable' => true,
            'NoOfRooms' => 1,
            'MealType' => 'All',
        ],
    ];
    
    echo "Fetching availability...\n";
    $searchResponse = $hotelApi->searchHotel($searchData);
    
    $availableRooms = [];
    if (isset($searchResponse['HotelResult'][0]['Rooms'])) {
        $availableRooms = $searchResponse['HotelResult'][0]['Rooms'];
    } elseif (isset($searchResponse['Hotels'][0]['Rooms'])) {
        $availableRooms = $searchResponse['Hotels'][0]['Rooms'];
    }
    
    echo "Found " . count($availableRooms) . " rooms.\n";
    
    if (!empty($availableRooms)) {
        // Inspect first room
        echo "First Room Structure:\n";
        print_r($availableRooms[0]);
        
        // Extract names
        $roomNames = [];
        foreach ($availableRooms as $room) {
            $name = is_array($room['Name']) ? ($room['Name'][0] ?? '') : ($room['Name'] ?? '');
            if (!empty($name)) {
                $roomNames[] = $name;
            }
        }
        $roomNames = array_unique($roomNames);
        echo "Unique Names to Translate:\n";
        print_r($roomNames);
        
        // Try translate
        echo "Attempting translation...\n";
        $translated = $translationService->translateStrings($roomNames, 'en', 'ar');
        echo "Translation Result:\n";
        print_r($translated);
    } else {
        echo "No rooms found. Check availability params or API.\n";
        print_r($searchResponse);
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
