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
        // Clear existing data to avoid conflicts with new structure
        \Illuminate\Support\Facades\DB::table('otps')->delete();

        Schema::table('otps', function (Blueprint $table) {
            $table->dropColumn('identifier');
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->string('identifier');
        });
    }
};
