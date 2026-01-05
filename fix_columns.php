<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Schema::table('flight_bookings', function (Blueprint $table) {
    if (Schema::hasColumn('flight_bookings', 'origin_airport_id')) {
        $table->dropColumn('origin_airport_id');
    }
    if (Schema::hasColumn('flight_bookings', 'destination_airport_id')) {
        $table->dropColumn('destination_airport_id');
    }
});
echo "Done\n";
