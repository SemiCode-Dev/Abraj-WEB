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
        Schema::table('cities', function (Blueprint $table) {
            if (!Schema::hasColumn('cities', 'hotels_count')) {
                $table->integer('hotels_count')->default(0);
            }
            if (!Schema::hasColumn('cities', 'image_url')) {
                $table->string('image_url')->nullable();
            }
            if (!Schema::hasColumn('cities', 'display_name_en')) {
                $table->string('display_name_en')->nullable();
            }
            if (!Schema::hasColumn('cities', 'display_name_ar')) {
                $table->string('display_name_ar')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['hotels_count', 'image_url', 'display_name_en', 'display_name_ar']);
        });
    }
};
