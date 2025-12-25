<?php

namespace Database\Factories;

use App\Constants\BookingStatus;
use App\Constants\PaymentStatus;
use App\Models\HotelBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HotelBookingFactory extends Factory
{
    protected $model = HotelBooking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'booking_reference' => 'BK-'.strtoupper(Str::random(10)),
            'hotel_code' => $this->faker->numerify('######'),
            'hotel_name' => $this->faker->company.' Hotel',
            'room_code' => 'ROOM-'.$this->faker->numerify('####'),
            'room_name' => $this->faker->randomElement(['Deluxe Room', 'Standard Room', 'Suite', 'Executive Room']),
            'check_in' => now()->addDays(30),
            'check_out' => now()->addDays(32),
            'nights' => 2,
            'total_price' => $this->faker->randomFloat(2, 200, 2000),
            'currency' => $this->faker->randomElement(['SAR', 'USD', 'EUR']),
            'guest_name' => $this->faker->name,
            'guest_email' => $this->faker->safeEmail,
            'guest_phone' => $this->faker->numerify('##########'),
            'phone_country_code' => '966',
            'booking_status' => BookingStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ];
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_status' => BookingStatus::CONFIRMED,
            'payment_status' => PaymentStatus::PAID,
            'tbo_booking_id' => 'TBO-'.$this->faker->numerify('##########'),
            'confirmation_number' => 'CONF-'.$this->faker->numerify('########'),
            'payment_reference' => 'PAY-'.$this->faker->numerify('##########'),
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_status' => BookingStatus::CANCELLED,
            'payment_status' => PaymentStatus::FAILED,
        ]);
    }

    /**
     * Indicate that the booking has failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_status' => BookingStatus::FAILED,
            'payment_status' => PaymentStatus::FAILED,
        ]);
    }

    /**
     * Indicate that the payment is paid but booking not confirmed yet.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => PaymentStatus::PAID,
            'payment_reference' => 'PAY-'.$this->faker->numerify('##########'),
        ]);
    }
}
