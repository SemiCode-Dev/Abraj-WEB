<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Running manual SQL updates...\n";

try {
    DB::statement("ALTER TABLE flight_bookings DROP FOREIGN KEY IF EXISTS flight_bookings_origin_city_id_foreign");
    DB::statement("ALTER TABLE flight_bookings DROP FOREIGN KEY IF EXISTS flight_bookings_destination_city_id_foreign");
} catch (\Exception $e) { echo "Warn: " . $e->getMessage() . "\n"; }

try {
    DB::statement("ALTER TABLE flight_bookings DROP COLUMN IF EXISTS origin_city_id");
    DB::statement("ALTER TABLE flight_bookings DROP COLUMN IF EXISTS destination_city_id");
} catch (\Exception $e) { echo "Warn: " . $e->getMessage() . "\n"; }

try {
    DB::statement("ALTER TABLE flight_bookings DROP COLUMN IF EXISTS origin_airport_id");
    DB::statement("ALTER TABLE flight_bookings DROP COLUMN IF EXISTS destination_airport_id");
} catch (\Exception $e) { echo "Warn: " . $e->getMessage() . "\n"; }

echo "Adding airport columns...\n";
DB::statement("ALTER TABLE flight_bookings ADD origin_airport_id bigint unsigned null after origin_country_id");
DB::statement("ALTER TABLE flight_bookings ADD destination_airport_id bigint unsigned null after destination_country_id");

echo "Adding foreign keys...\n";
DB::statement("ALTER TABLE flight_bookings ADD CONSTRAINT flight_bookings_origin_airport_id_foreign FOREIGN KEY (origin_airport_id) REFERENCES airports(id) ON DELETE SET NULL");
DB::statement("ALTER TABLE flight_bookings ADD CONSTRAINT flight_bookings_destination_airport_id_foreign FOREIGN KEY (destination_airport_id) REFERENCES airports(id) ON DELETE SET NULL");

echo "Inserting into migrations table...\n";
DB::table('migrations')->insert(['migration' => '2026_01_05_124238_modify_flight_bookings_table_add_airport_columns', 'batch' => 99]);

echo "Done\n";
