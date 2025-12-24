<?php

namespace Tests;

use App\Models\User;
use App\Models\HotelBooking;
use App\Constants\BookingStatus;
use App\Constants\PaymentStatus;
use Illuminate\Support\Str;

/**
 * Test Helper Class
 * Provides utility methods for testing booking and payment flows
 */
class TestHelper
{
    /**
     * Create a test user
     */
    public static function createTestUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'email' => 'test' . time() . '@example.com',
            'name' => 'Test User',
            'phone' => '1234567890',
            'phone_country_code' => '966',
        ], $attributes));
    }

    /**
     * Create a pending booking
     */
    public static function createPendingBooking(User $user, array $attributes = []): HotelBooking
    {
        return HotelBooking::factory()->create(array_merge([
            'user_id' => $user->id,
            'booking_status' => BookingStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ], $attributes));
    }

    /**
     * Create a confirmed booking
     */
    public static function createConfirmedBooking(User $user, array $attributes = []): HotelBooking
    {
        return HotelBooking::factory()->confirmed()->create(array_merge([
            'user_id' => $user->id,
        ], $attributes));
    }

    /**
     * Generate mock payment callback data
     */
    public static function mockPaymentCallback(string $merchantReference, bool $success = true): array
    {
        $status = $success ? '14' : '00'; // 14 = Success, 00 = Failure

        return [
            'merchant_reference' => $merchantReference,
            'status' => $status,
            'fort_id' => 'MOCK_PAYMENT_' . time(),
            'payment_option' => 'VISA',
            'amount' => 50000, // 500.00 in smallest unit
            'currency' => 'SAR',
            'response_message' => $success ? 'Success' : 'Payment declined',
            'response_code' => $success ? '14000' : '00001',
        ];
    }

    /**
     * Generate mock TBO search response
     */
    public static function mockTboSearchResponse(string $hotelCode = '1491912'): array
    {
        return [
            'Status' => [
                'Code' => 200,
                'Description' => 'Success'
            ],
            'HotelResult' => [
                [
                    'HotelCode' => $hotelCode,
                    'HotelName' => 'Test Hotel',
                    'Rooms' => [
                        [
                            'BookingCode' => 'ROOM-' . Str::random(8),
                            'RoomTypeName' => 'Deluxe Room',
                            'Price' => [
                                'PublishedPrice' => 500.00,
                                'CurrencyCode' => 'SAR',
                            ],
                            'AvailableRooms' => 5,
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Generate mock TBO PreBook response
     */
    public static function mockTboPreBookResponse(string $bookingCode, bool $success = true): array
    {
        if (!$success) {
            return [
                'Status' => [
                    'Code' => 400,
                    'Description' => 'Session Expired'
                ]
            ];
        }

        return [
            'Status' => [
                'Code' => 200,
                'Description' => 'Success'
            ],
            'BookingCode' => $bookingCode,
            'BookingReferenceId' => 'TBO-' . Str::random(10),
            'TotalFare' => 500.00,
            'Price' => [
                'TotalDisplayFare' => 500.00,
                'CurrencyCode' => 'SAR',
            ]
        ];
    }

    /**
     * Generate mock TBO Book response
     */
    public static function mockTboBookResponse(bool $success = true): array
    {
        if (!$success) {
            return [
                'Status' => [
                    'Code' => 400,
                    'Description' => 'Booking Failed'
                ]
            ];
        }

        return [
            'Status' => [
                'Code' => 200,
                'Description' => 'Success'
            ],
            'BookingId' => 'TBO-' . time(),
            'ConfirmationNo' => 'CONF-' . Str::random(8),
            'BookingRefNo' => 'REF-' . Str::random(10),
            'Status' => 'Confirmed',
        ];
    }

    /**
     * Assert booking has correct status
     */
    public static function assertBookingStatus(
        HotelBooking $booking,
        string $expectedBookingStatus,
        string $expectedPaymentStatus
    ): void {
        \PHPUnit\Framework\Assert::assertEquals(
            $expectedBookingStatus,
            $booking->booking_status,
            "Expected booking status to be {$expectedBookingStatus}, got {$booking->booking_status}"
        );

        \PHPUnit\Framework\Assert::assertEquals(
            $expectedPaymentStatus,
            $booking->payment_status,
            "Expected payment status to be {$expectedPaymentStatus}, got {$booking->payment_status}"
        );
    }

    /**
     * Assert booking reference format
     */
    public static function assertValidBookingReference(string $reference): void
    {
        \PHPUnit\Framework\Assert::assertMatchesRegularExpression(
            '/^BK-[A-Z0-9]{10}$/',
            $reference,
            "Booking reference {$reference} does not match expected format BK-XXXXXXXXXX"
        );
    }

    /**
     * Get test hotel search data
     */
    public static function getTestSearchData(string $hotelCode = '1491912', int $daysFromNow = 30): array
    {
        return [
            'HotelCodes' => $hotelCode,
            'CheckInDate' => now()->addDays($daysFromNow)->format('Y-m-d'),
            'CheckOutDate' => now()->addDays($daysFromNow + 2)->format('Y-m-d'),
            'GuestNationality' => 'SA',
            'NoOfRooms' => 1,
            'RoomGuests' => [
                ['NoOfAdults' => 2, 'NoOfChild' => 0]
            ]
        ];
    }

    /**
     * Get test booking data
     */
    public static function getTestBookingData(string $hotelCode = '1491912', string $roomCode = null): array
    {
        return [
            'hotel_code' => $hotelCode,
            'hotel_name' => 'Test Hotel',
            'room_code' => $roomCode ?? 'ROOM-' . Str::random(8),
            'room_name' => 'Deluxe Room',
            'check_in' => now()->addDays(30)->format('Y-m-d'),
            'check_out' => now()->addDays(32)->format('Y-m-d'),
            'total_price' => 500.00,
            'currency' => 'SAR',
            'guest_name' => 'John Doe',
            'guest_email' => 'john.doe@example.com',
            'guest_phone' => '555123456',
            'phone_country_code' => '966',
        ];
    }

    /**
     * Clean up test bookings
     */
    public static function cleanupTestBookings(): void
    {
        HotelBooking::where('booking_reference', 'like', 'BK-TEST%')->delete();
    }

    /**
     * Log test message
     */
    public static function log(string $message, array $context = []): void
    {
        \Illuminate\Support\Facades\Log::info("TEST: {$message}", $context);
    }
}
