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
        Schema::table('visa_bookings', function (Blueprint $table) {
            $table->foreignId('nationality_id')->nullable()->after('country_id')->constrained('countries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_bookings', function (Blueprint $table) {
            $table->dropForeign(['nationality_id']);
            $table->dropColumn('nationality_id');
        });
    }
};
