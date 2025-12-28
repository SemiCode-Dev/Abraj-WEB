<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$commands = [
    "ALTER TABLE `car_rental_bookings` DROP FOREIGN KEY `car_rental_bookings_destination_city_id_foreign`",
    "ALTER TABLE `car_rental_bookings` DROP INDEX `car_rental_bookings_destination_city_id_foreign`",
    "ALTER TABLE `transfer_bookings` DROP FOREIGN KEY `transfer_bookings_destination_city_id_foreign`",
    "ALTER TABLE `transfer_bookings` DROP INDEX `transfer_bookings_destination_city_id_foreign`",
    "ALTER TABLE `flight_bookings` DROP FOREIGN KEY `flight_bookings_origin_city_id_foreign`",
    "ALTER TABLE `flight_bookings` DROP INDEX `flight_bookings_origin_city_id_foreign`",
    "ALTER TABLE `flight_bookings` DROP FOREIGN KEY `flight_bookings_destination_city_id_foreign`",
    "ALTER TABLE `flight_bookings` DROP INDEX `flight_bookings_destination_city_id_foreign`",
];

foreach ($commands as $sql) {
    echo "Executing: $sql\n";
    try {
        DB::statement($sql);
        echo "  SUCCESS\n";
    } catch (\Throwable $e) {
        echo "  FAILED: " . $e->getMessage() . "\n";
    }
}
