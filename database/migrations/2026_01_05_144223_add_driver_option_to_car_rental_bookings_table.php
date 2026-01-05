<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_rental_bookings', function (Blueprint $table) {
            $table->string('driver_option')->after('return_time')->default('without_driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_rental_bookings', function (Blueprint $table) {
            $table->dropColumn('driver_option');
        });
    }
};
