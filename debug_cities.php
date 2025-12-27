<?php

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    echo "Checking Bahrain...\n";
    $country = Country::where('name_ar', 'like', '%البحرين%')
                      ->orWhere('name', 'like', '%Bahrain%')
                      ->first();

    if ($country) {
        echo "Found Bahrain: {$country->name} (ID: {$country->id})\n";
        $count = City::where('country_id', $country->id)->count();
        echo "City Count: {$count}\n";
    } else {
        echo "Bahrain not found in DB.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
