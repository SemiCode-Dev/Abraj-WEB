<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$countries = new \PragmaRX\Countries\Package\Countries();
$country = $countries->where('cca2', 'SA')->first();
$pkgCities = $country->hydrate('cities')->cities;

echo "First City Dump:\n";
print_r($pkgCities->first());
