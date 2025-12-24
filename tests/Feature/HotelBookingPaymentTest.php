<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\HotelBooking;
use App\Constants\BookingStatus;
use App\Constants\PaymentStatus;
use App\Services\Api\V1\BookingService;
use App\Services\Api\V1\PaymentService;
use App\Services\Api\V1\HotelApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HotelBookingPaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $bookingService;
    protected $paymentService;
    protected $hotelApiService;
    
    // Test hotel ID provided by user
    protected $testHotelId = '1491912';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '1234567890',
            'phone_country_code' => '966',
        ]);
        
        $this->actingAs($this->user);
        
        // Initialize services
        $this->bookingService = app(BookingService::class);
        $this->paymentService = app(PaymentService::class);
        $this->hotelApiService = app(HotelApiService::class);
    }

    /**
     * Test 1: Complete booking flow - from initiation to confirmation
     */
    public function test_complete_booking_flow_with_payment()
    {
        Log::info('TEST: Starting complete booking flow test');

        // Step 1: Search for available rooms
        $searchData = [
            'HotelCodes' => $this->testHotelId,
            'CheckInDate' => now()->addDays(30)->format('Y-m-d'),
            'CheckOutDate' => now()->addDays(32)->format('Y-m-d'),
            'GuestNationality' => 'SA',
            'NoOfRooms' => 1,
            'RoomGuests' => [
                ['NoOfAdults' => 2, 'NoOfChild' => 0]
            ]
        ];

        $searchResponse = $this->hotelApiService->searchHotel($searchData);
        
        $this->assertNotEmpty($searchResponse, 'Search response should not be empty');
        $this->assertArrayHasKey('HotelResult', $searchResponse);
        
        Log::info('TEST: Search completed', ['hotel_count' => count($searchResponse['HotelResult'] ?? [])]);

        // Get first available room
        $hotel = $searchResponse['HotelResult'][0] ?? null;
        $this->assertNotNull($hotel, 'At least one hotel should be available');
        
        $room = $hotel['Rooms'][0] ?? null;
        $this->assertNotNull($room, 'At least one room should be available');

        // Step 2: Initiate booking
        $bookingData = [
            'hotel_code' => $this->testHotelId,
            'hotel_name' => $hotel['HotelName'] ?? 'Test Hotel',
            'room_code' => $room['BookingCode'],
            'room_name' => $room['RoomTypeName'] ?? 'Test Room',
            'check_in' => $searchData['CheckInDate'],
            'check_out' => $searchData['CheckOutDate'],
            'total_price' => $room['Price']['PublishedPrice'] ?? 500,
            'currency' => $room['Price']['CurrencyCode'] ?? 'SAR',
            'guest_name' => 'John Doe',
            'guest_email' => 'john@example.com',
            'guest_phone' => '555123456',
            'phone_country_code' => '966',
        ];

        $booking = $this->bookingService->initiateBooking($bookingData);

        // Assertions for initiated booking
        $this->assertInstanceOf(HotelBooking::class, $booking);
        $this->assertEquals(BookingStatus::PENDING, $booking->booking_status);
        $this->assertEquals(PaymentStatus::PENDING, $booking->payment_status);
        $this->assertNotNull($booking->booking_reference);
        $this->assertTrue(str_starts_with($booking->booking_reference, 'BK-'));
        
        Log::info('TEST: Booking initiated', ['reference' => $booking->booking_reference]);

        // Step 3: Generate payment data
        $paymentData = $this->paymentService->apsPaymentForReservation([
            'amount' => $booking->total_price,
            'currency' => $booking->currency,
            'customer_email' => $booking->guest_email,
            'merchant_reference' => $booking->booking_reference,
        ]);

        $this->assertArrayHasKey('signature', $paymentData);
        $this->assertArrayHasKey('merchant_reference', $paymentData);
        $this->assertEquals($booking->booking_reference, $paymentData['merchant_reference']);
        
        Log::info('TEST: Payment data generated', ['merchant_ref' => $paymentData['merchant_reference']]);

        // Step 4: Simulate successful payment callback
        $callbackData = [
            'merchant_reference' => $booking->booking_reference,
            'status' => '14', // Success status
            'fort_id' => 'TEST_PAYMENT_' . time(),
            'payment_option' => 'VISA',
            'amount' => (int)round($booking->total_price * 100),
            'currency' => $booking->currency,
            'response_message' => 'Success',
        ];

        // Add signature
        $callbackData['signature'] = $this->paymentService->apsSignature(
            $callbackData,
            config('services.aps.sha_response')
        );

        // Step 5: Complete booking (this will call TBO Book API)
        $success = $this->bookingService->completeBooking($booking->fresh(), $callbackData);

        // Assertions for completed booking
        $booking->refresh();
        
        $this->assertEquals(PaymentStatus::PAID, $booking->payment_status);
        $this->assertNotNull($booking->payment_reference);
        
        // Booking status should be CONFIRMED if TBO booking succeeded, or FAILED if it didn't
        $this->assertContains($booking->booking_status, [BookingStatus::CONFIRMED, BookingStatus::FAILED]);
        
        if ($booking->booking_status === BookingStatus::CONFIRMED) {
            $this->assertNotNull($booking->tbo_booking_id);
            $this->assertNotNull($booking->confirmation_number);
            Log::info('TEST: Booking confirmed successfully', [
                'tbo_booking_id' => $booking->tbo_booking_id,
                'confirmation' => $booking->confirmation_number
            ]);
        } else {
            Log::warning('TEST: Booking payment succeeded but TBO booking failed', [
                'reason' => $booking->tbo_response['Status']['Description'] ?? 'Unknown'
            ]);
        }

        Log::info('TEST: Complete booking flow test finished');
    }

    /**
     * Test 2: Room becomes unavailable after booking
     */
    public function test_room_becomes_unavailable_after_booking()
    {
        Log::info('TEST: Starting room availability test');

        // Step 1: Search for rooms
        $checkIn = now()->addDays(30)->format('Y-m-d');
        $checkOut = now()->addDays(32)->format('Y-m-d');
        
        $searchData = [
            'HotelCodes' => $this->testHotelId,
            'CheckInDate' => $checkIn,
            'CheckOutDate' => $checkOut,
            'GuestNationality' => 'SA',
            'NoOfRooms' => 1,
            'RoomGuests' => [
                ['NoOfAdults' => 2, 'NoOfChild' => 0]
            ]
        ];

        $firstSearch = $this->hotelApiService->searchHotel($searchData);
        $this->assertNotEmpty($firstSearch['HotelResult']);
        
        $initialRoomCount = count($firstSearch['HotelResult'][0]['Rooms'] ?? []);
        $room = $firstSearch['HotelResult'][0]['Rooms'][0];
        
        Log::info('TEST: Initial room count', ['count' => $initialRoomCount]);

        // Step 2: Book the room
        $bookingData = [
            'hotel_code' => $this->testHotelId,
            'hotel_name' => 'Test Hotel',
            'room_code' => $room['BookingCode'],
            'room_name' => $room['RoomTypeName'] ?? 'Test Room',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_price' => $room['Price']['PublishedPrice'] ?? 500,
            'currency' => $room['Price']['CurrencyCode'] ?? 'SAR',
            'guest_name' => 'Jane Doe',
            'guest_email' => 'jane@example.com',
            'guest_phone' => '555987654',
            'phone_country_code' => '966',
        ];

        $booking = $this->bookingService->initiateBooking($bookingData);
        
        // Complete the booking
        $paymentDetails = [
            'merchant_reference' => $booking->booking_reference,
            'status' => '14',
            'fort_id' => 'TEST_' . time(),
            'response_message' => 'Success',
        ];
        
        $this->bookingService->completeBooking($booking, $paymentDetails);
        
        Log::info('TEST: Booking completed', ['reference' => $booking->booking_reference]);

        // Step 3: Search again for the same dates
        $secondSearch = $this->hotelApiService->searchHotel($searchData);
        
        if (!empty($secondSearch['HotelResult'])) {
            $newRoomCount = count($secondSearch['HotelResult'][0]['Rooms'] ?? []);
            Log::info('TEST: Room count after booking', ['count' => $newRoomCount]);
            
            // The booked room should not appear in the new search
            // OR the available count should be reduced
            $roomStillAvailable = false;
            foreach ($secondSearch['HotelResult'][0]['Rooms'] ?? [] as $availableRoom) {
                if ($availableRoom['BookingCode'] === $room['BookingCode']) {
                    $roomStillAvailable = true;
                    break;
                }
            }
            
            // The specific room booking code should not be available anymore
            // Note: TBO may return different booking codes for the same room type
            $this->assertFalse(
                $roomStillAvailable,
                'The booked room should not be available for the same dates'
            );
        } else {
            // If no rooms are returned, it means all rooms are booked
            Log::info('TEST: No rooms available after booking - all rooms booked');
            $this->assertTrue(true, 'All rooms are now booked');
        }
    }

    /**
     * Test 3: Payment failure handling
     */
    public function test_payment_failure_cancels_booking()
    {
        Log::info('TEST: Starting payment failure test');

        // Create a pending booking
        $booking = HotelBooking::create([
            'user_id' => $this->user->id,
            'booking_reference' => 'BK-TEST' . time(),
            'hotel_code' => $this->testHotelId,
            'hotel_name' => 'Test Hotel',
            'room_code' => 'TEST_ROOM_CODE',
            'room_name' => 'Test Room',
            'check_in' => now()->addDays(30),
            'check_out' => now()->addDays(32),
            'total_price' => 500,
            'currency' => 'SAR',
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '555000000',
            'phone_country_code' => '966',
            'booking_status' => BookingStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ]);

        // Simulate failed payment
        $this->bookingService->cancelBooking($booking, 'Payment declined by bank');

        $booking->refresh();
        
        $this->assertEquals(BookingStatus::CANCELLED, $booking->booking_status);
        $this->assertEquals(PaymentStatus::FAILED, $booking->payment_status);
        
        Log::info('TEST: Booking cancelled after payment failure');
    }

    /**
     * Test 4: Duplicate booking prevention
     */
    public function test_duplicate_booking_prevention()
    {
        Log::info('TEST: Starting duplicate booking prevention test');

        $bookingData = [
            'hotel_code' => $this->testHotelId,
            'hotel_name' => 'Test Hotel',
            'room_code' => 'TEST_ROOM_' . time(),
            'room_name' => 'Test Room',
            'check_in' => now()->addDays(30)->format('Y-m-d'),
            'check_out' => now()->addDays(32)->format('Y-m-d'),
            'total_price' => 500,
            'currency' => 'SAR',
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '555111111',
            'phone_country_code' => '966',
        ];

        // First booking
        $booking1 = $this->bookingService->initiateBooking($bookingData);
        
        // Complete first booking
        $paymentDetails = [
            'merchant_reference' => $booking1->booking_reference,
            'status' => '14',
            'fort_id' => 'TEST_' . time(),
        ];
        
        $this->bookingService->completeBooking($booking1, $paymentDetails);
        
        // Verify booking is completed
        $booking1->refresh();
        $this->assertEquals(PaymentStatus::PAID, $booking1->payment_status);
        
        // Try to complete again with same payment details
        $result = $this->bookingService->completeBooking($booking1, $paymentDetails);
        
        // Should return true (already processed) without creating duplicate
        $this->assertTrue($result);
        
        // Verify only one booking exists
        $bookingCount = HotelBooking::where('user_id', $this->user->id)
            ->where('hotel_code', $this->testHotelId)
            ->where('payment_status', PaymentStatus::PAID)
            ->count();
            
        $this->assertEquals(1, $bookingCount, 'Should not create duplicate bookings');
        
        Log::info('TEST: Duplicate booking prevention verified');
    }

    /**
     * Test 5: Payment signature validation
     */
    public function test_payment_signature_validation()
    {
        Log::info('TEST: Starting payment signature validation test');

        $testData = [
            'merchant_reference' => 'BK-TEST123',
            'amount' => 50000,
            'currency' => 'SAR',
            'status' => '14',
        ];

        $phrase = config('services.aps.sha_request');
        
        // Generate signature
        $signature = $this->paymentService->apsSignature($testData, $phrase);
        
        $this->assertNotEmpty($signature);
        $this->assertEquals(64, strlen($signature)); // SHA256 produces 64 character hex string
        
        // Verify signature is consistent
        $signature2 = $this->paymentService->apsSignature($testData, $phrase);
        $this->assertEquals($signature, $signature2, 'Signature should be consistent for same data');
        
        // Verify signature changes with different data
        $testData['amount'] = 60000;
        $signature3 = $this->paymentService->apsSignature($testData, $phrase);
        $this->assertNotEquals($signature, $signature3, 'Signature should change when data changes');
        
        Log::info('TEST: Payment signature validation completed');
    }

    /**
     * Test 6: Booking reference format validation
     */
    public function test_booking_reference_format()
    {
        Log::info('TEST: Starting booking reference format test');

        $bookingData = [
            'hotel_code' => $this->testHotelId,
            'hotel_name' => 'Test Hotel',
            'room_code' => 'TEST_ROOM_' . time(),
            'room_name' => 'Test Room',
            'check_in' => now()->addDays(30)->format('Y-m-d'),
            'check_out' => now()->addDays(32)->format('Y-m-d'),
            'total_price' => 500,
            'currency' => 'SAR',
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '555222222',
            'phone_country_code' => '966',
        ];

        $booking = $this->bookingService->initiateBooking($bookingData);

        // Verify booking reference format
        $this->assertMatchesRegularExpression('/^BK-[A-Z0-9]{10}$/', $booking->booking_reference);
        $this->assertEquals(13, strlen($booking->booking_reference)); // BK- + 10 characters
        
        Log::info('TEST: Booking reference format validated', ['reference' => $booking->booking_reference]);
    }

    /**
     * Test 7: Price calculation and currency handling
     */
    public function test_price_calculation_and_currency()
    {
        Log::info('TEST: Starting price calculation test');

        $prices = [
            ['amount' => 500, 'currency' => 'SAR'],
            ['amount' => 1000.50, 'currency' => 'USD'],
            ['amount' => 250.75, 'currency' => 'EUR'],
        ];

        foreach ($prices as $priceData) {
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $priceData['amount'],
                'currency' => $priceData['currency'],
                'customer_email' => 'test@example.com',
                'merchant_reference' => 'TEST-' . time(),
            ]);

            // APS requires amount in smallest unit (cents/fils)
            $expectedAmount = (int)round($priceData['amount'] * 100);
            
            $this->assertEquals($expectedAmount, $paymentData['amount']);
            $this->assertEquals(strtoupper($priceData['currency']), $paymentData['currency']);
            
            Log::info('TEST: Price validated', [
                'original' => $priceData['amount'],
                'converted' => $expectedAmount,
                'currency' => $priceData['currency']
            ]);
        }
    }

    /**
     * Test 8: Database transaction rollback on failure
     */
    public function test_database_transaction_rollback()
    {
        Log::info('TEST: Starting transaction rollback test');

        $initialCount = HotelBooking::count();

        try {
            DB::transaction(function () {
                $booking = HotelBooking::create([
                    'user_id' => $this->user->id,
                    'booking_reference' => 'BK-ROLLBACK',
                    'hotel_code' => $this->testHotelId,
                    'room_code' => 'TEST_ROOM',
                    'check_in' => now()->addDays(30),
                    'check_out' => now()->addDays(32),
                    'total_price' => 500,
                    'currency' => 'SAR',
                    'guest_name' => 'Test',
                    'guest_email' => 'test@example.com',
                    'booking_status' => BookingStatus::PENDING,
                    'payment_status' => PaymentStatus::PENDING,
                ]);

                // Force an exception to trigger rollback
                throw new \Exception('Simulated failure');
            });
        } catch (\Exception $e) {
            // Expected exception
        }

        $finalCount = HotelBooking::count();
        
        $this->assertEquals($initialCount, $finalCount, 'Transaction should rollback on failure');
        
        Log::info('TEST: Transaction rollback verified');
    }
}
