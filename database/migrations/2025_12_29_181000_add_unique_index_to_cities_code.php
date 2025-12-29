<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Truncate table to remove potential duplicates before adding unique index
        DB::table('cities')->truncate();

        Schema::table('cities', function (Blueprint $table) {
            // 2. Add unique index to 'code' to ensure upsert works correctly
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
    }
};
