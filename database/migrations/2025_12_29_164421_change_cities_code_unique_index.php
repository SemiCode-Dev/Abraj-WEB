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
        try {
            Schema::table('cities', function (Blueprint $table) {
                // Drop old non-composite unique index if it exists
                $table->dropUnique(['code']);
            });
        } catch (\Exception $e) {
            // If the index doesn't exist, we can ignore the error and proceed
        }

        // Truncate to remove contaminated data
        DB::table('cities')->truncate();

        Schema::table('cities', function (Blueprint $table) {
            // Add composite unique index
            $table->unique(['code', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique(['code', 'country_id']);
            $table->unique('code');
        });
    }
};
