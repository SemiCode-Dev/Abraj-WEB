<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        // Disable foreign key checks, truncate, then re-enable
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
