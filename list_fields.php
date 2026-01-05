<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fields = \Illuminate\Support\Facades\DB::select('desc flight_bookings');
foreach ($fields as $field) {
    echo $field->Field . "\n";
}
