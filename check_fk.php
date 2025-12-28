<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$tables = [
    'car_rental_bookings' => ['destination_city_id'],
    'transfer_bookings' => ['destination_city_id'],
    'flight_bookings' => ['origin_city_id', 'destination_city_id'],
];

foreach ($tables as $table => $columns) {
    echo "Checking table: $table\n";
    foreach ($columns as $column) {
        $fk = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ? 
            AND COLUMN_NAME = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        if (!empty($fk)) {
            echo "  Column '$column': Found FK constraint '{$fk[0]->CONSTRAINT_NAME}'\n";
        } else {
            echo "  Column '$column': No FK constraint found.\n";
        }
    }
}
