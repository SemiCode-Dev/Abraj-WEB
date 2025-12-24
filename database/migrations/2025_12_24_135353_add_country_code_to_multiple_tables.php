<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'phone_country_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone_country_code', 10)->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('hotel_bookings', 'phone_country_code')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->string('phone_country_code', 10)->nullable()->after('guest_email');
            });
        }

        if (!Schema::hasColumn('package_contacts', 'phone_country_code')) {
            Schema::table('package_contacts', function (Blueprint $table) {
                $table->string('phone_country_code', 10)->nullable()->after('email');
            });
        }

        // Ensure other tables have it too (though they should based on my check)
        $otherTables = ['flight_bookings', 'car_rental_bookings', 'transfer_bookings', 'visa_bookings'];
        foreach ($otherTables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'phone_country_code')) {
                Schema::table($table, function (Blueprint $tableGroup) {
                    $tableGroup->string('phone_country_code', 10)->nullable()->after('email');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_country_code');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn('phone_country_code');
        });

        Schema::table('package_contacts', function (Blueprint $table) {
            $table->dropColumn('phone_country_code');
        });
        
        $otherTables = ['flight_bookings', 'car_rental_bookings', 'transfer_bookings', 'visa_bookings'];
        foreach ($otherTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'phone_country_code')) {
                Schema::table($table, function (Blueprint $tableGroup) {
                    $tableGroup->dropColumn('phone_country_code');
                });
            }
        }
    }
};
