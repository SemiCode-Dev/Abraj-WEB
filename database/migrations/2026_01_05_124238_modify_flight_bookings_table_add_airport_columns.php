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
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropForeign(['origin_city_id']);
            $table->dropForeign(['destination_city_id']);
            $table->dropColumn(['origin_city_id', 'destination_city_id']);
            
            $table->foreignId('origin_airport_id')->nullable()->after('origin_country_id')->constrained('airports')->onDelete('set null');
            $table->foreignId('destination_airport_id')->nullable()->after('destination_country_id')->constrained('airports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropForeign(['origin_airport_id']);
            $table->dropForeign(['destination_airport_id']);
            $table->dropColumn(['origin_airport_id', 'destination_airport_id']);

            $table->foreignId('origin_city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('destination_city_id')->nullable()->constrained('cities')->onDelete('set null');
        });
    }
};
