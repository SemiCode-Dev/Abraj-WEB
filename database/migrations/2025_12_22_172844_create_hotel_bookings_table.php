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
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('booking_reference')->unique();
            $table->string('hotel_code')->index();
            $table->string('hotel_name')->nullable();
            $table->string('room_code')->index();
            $table->string('room_name')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 12, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone')->nullable();
            $table->string('booking_status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('tbo_booking_id')->nullable()->index();
            $table->string('confirmation_number')->nullable()->index();
            $table->json('tbo_response')->nullable();
            $table->string('payment_reference')->nullable()->index();
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
