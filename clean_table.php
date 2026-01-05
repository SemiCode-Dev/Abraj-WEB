<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking columns...\n";
$columns = Schema::getColumnListing('flight_bookings');
print_r($columns);

Schema::table('flight_bookings', function (Blueprint $table) {
    $colsToDrop = ['origin_airport_id', 'destination_airport_id'];
    foreach ($colsToDrop as $col) {
        if (Schema::hasColumn('flight_bookings', $col)) {
            echo "Dropping $col...\n";
            $table->dropColumn($col);
        }
    }
});

// Also make sure city columns exist so migration can drop them
Schema::table('flight_bookings', function (Blueprint $table) {
    if (!Schema::hasColumn('flight_bookings', 'origin_city_id')) {
        echo "Adding dummy origin_city_id...\n";
        $table->unsignedBigInteger('origin_city_id')->nullable();
    }
    if (!Schema::hasColumn('flight_bookings', 'destination_city_id')) {
        echo "Adding dummy destination_city_id...\n";
        $table->unsignedBigInteger('destination_city_id')->nullable();
    }
});

echo "Final columns:\n";
print_r(Schema::getColumnListing('flight_bookings'));
