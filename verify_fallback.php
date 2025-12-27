<?php

use App\Http\Controllers\Web\V1\LocationController;
use App\Models\Country;
use App\Services\Api\V1\HotelApiService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    echo "Verifying LocationController Fallback for Bahrain...\n";

    // 1. Get Bahrain ID
    $country = Country::where('name', 'like', '%Bahrain%')->orWhere('name_ar', 'like', '%البحرين%')->first();
    if (! $country) {
        exit("Bahrain not found in DB.\n");
    }
    echo "Country: {$country->name} (ID: {$country->id}, Code: {$country->code})\n";

    // 2. Instantiate Controller
    $apiService = new HotelApiService;
    $controller = new LocationController($apiService);

    // 3. Call getCities
    echo "Calling getCities({$country->id})...\n";
    $response = $controller->getCities($country->id);

    // 4. Check Response
    $cities = $response->getData(); // JsonResponse

    echo 'Cities found: '.count($cities)."\n";

    if (count($cities) > 0) {
        $first = $cities[0];
        echo 'First City: '.json_encode($first)."\n";
        echo 'Source: '.(is_numeric($first->id) ? 'Local DB (Likely)' : 'API (String ID)')."\n";
    } else {
        echo "FAILED: No cities returned.\n";
    }

} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
    echo $e->getTraceAsString();
}
