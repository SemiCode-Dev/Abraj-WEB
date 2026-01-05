<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Attempting to drop columns if they exist...\n";
try {
    DB::statement('ALTER TABLE flight_bookings DROP COLUMN origin_airport_id');
    echo "Dropped origin_airport_id\n";
} catch (\Exception $e) {
    echo "Error dropping origin_airport_id: " . $e->getMessage() . "\n";
}

try {
    DB::statement('ALTER TABLE flight_bookings DROP COLUMN destination_airport_id');
    echo "Dropped destination_airport_id\n";
} catch (\Exception $e) {
    echo "Error dropping destination_airport_id: " . $e->getMessage() . "\n";
}

echo "Done\n";
