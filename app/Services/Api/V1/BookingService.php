<?php

namespace App\Services\Api\V1;

use App\Models\HotelBooking;
use App\Constants\BookingStatus;
use App\Constants\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BookingService
{
    protected $tboService;

    public function __construct(HotelApiService $tboService)
    {
        $this->tboService = $tboService;
    }

    /**
     * Create a pending booking record in the database
     */
    public function initiateBooking(array $data): HotelBooking
    {
        Log::info('Initiating booking in database', $data);

        // 1. Pre-check availability with TBO before creating booking/payment
        // This ensures strictly that we don't take payment for unavailable rooms
        Log::info("Pre-checking availability for room: {$data['room_code']}");
        try {
            $preBookResponse = $this->tboService->preBook($data['room_code']);
            
            if (empty($preBookResponse['Status']['Code']) || $preBookResponse['Status']['Code'] != 200) {
                $msg = $preBookResponse['Status']['Description'] ?? 'Room is no longer available';
                Log::error("TBO PreBook Check Failed: $msg");
                throw new \Exception($msg);
            }
            
            // TBO often updates the BookingCode/RoomCode during PreBook
            if (!empty($preBookResponse['BookingCode'])) {
                Log::info("Updating room_code from PreBook response: {$data['room_code']} -> {$preBookResponse['BookingCode']}");
                $data['room_code'] = $preBookResponse['BookingCode'];
            }

            // Optional: Verify Price Integrity
            // if (isset($preBookResponse['TotalFare'])) { ... }

        } catch (\Exception $e) {
            // Rethrow so Controller handles it
            throw new \Exception("Room availability check failed: " . $e->getMessage());
        }

        return DB::transaction(function () use ($data) {
            $bookingReference = 'BK-' . strtoupper(Str::random(10));

            // Ensure price is numeric
            $totalPrice = str_replace(',', '', $data['total_price']);

            $booking = HotelBooking::create([
                'user_id' => auth()->id(),
                'booking_reference' => $bookingReference,
                'hotel_code' => $data['hotel_code'],
                'hotel_name' => $data['hotel_name'] ?? null,
                'hotel_name_ar' => $data['hotel_name_ar'] ?? null,
                'hotel_name_en' => $data['hotel_name_en'] ?? null,
                'room_code' => $data['room_code'],
                'room_name' => $data['room_name'] ?? null,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'total_price' => $totalPrice,
                'currency' => $data['currency'] ?? 'USD',
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'guest_phone' => $data['guest_phone'] ?? null,
                'phone_country_code' => $data['phone_country_code'] ?? null,
                'booking_status' => BookingStatus::PENDING,
                'payment_status' => PaymentStatus::PENDING,
            ]);

            Log::info('Booking record created', ['id' => $booking->id, 'reference' => $booking->booking_reference]);

            return $booking;
        });
    }

    /**
     * Complete the booking after successful payment
     */
    public function completeBooking(HotelBooking $booking, array $paymentDetails): bool
    {
        Log::info("Attempting to complete booking for Reference: {$booking->booking_reference}");

        if ($booking->payment_status === PaymentStatus::PAID && $booking->booking_status === BookingStatus::CONFIRMED) {
            return true; // Already processed
        }

        return DB::transaction(function () use ($booking, $paymentDetails) {
            // 1. Update Payment Status immediately to ensure we record the payment
            $booking->update([
                'payment_status' => PaymentStatus::PAID,
                'payment_reference' => $paymentDetails['fort_id'] ?? $paymentDetails['payment_reference'] ?? null,
                'payment_details' => $paymentDetails,
            ]);
            
            Log::info("Payment status updated to PAID for {$booking->booking_reference}");

            // 2. Pre-Book step (REQUIRED by TBO to validate session/price)
            Log::info("Calling TBO PreBook API for {$booking->booking_reference}");
            $preBookResponse = $this->tboService->preBook($booking->room_code);
            Log::info("TBO PreBook Response for {$booking->booking_reference}", ['response' => $preBookResponse]);

            $finalBookingCode = $booking->room_code;
            $tboBookingReferenceId = $booking->booking_reference; // Fallback
            $finalTotalFare = (float)$booking->total_price; // Default: what we have in DB

            if (isset($preBookResponse['Status']['Code']) && $preBookResponse['Status']['Code'] == 200) {
                // Use the refreshed code and reference from PreBook if available
                $finalBookingCode = $preBookResponse['BookingCode'] ?? $booking->room_code;
                $tboBookingReferenceId = $preBookResponse['BookingReferenceId'] ?? $booking->booking_reference;
                
                // CRITICAL: Update Price from PreBook response to ensure we match TBO requirements
                // TBO rejects booking if TotalFare doesn't match the latest PreBook price
                if (isset($preBookResponse['TotalFare'])) {
                    $newFare = (float)$preBookResponse['TotalFare'];
                    if (abs($newFare - $finalTotalFare) > 0.01) {
                        Log::warning("Price changed during PreBook! Old: $finalTotalFare, New: $newFare. Updating payload.");
                        $finalTotalFare = $newFare;
                    }
                } elseif (isset($preBookResponse['Price']['TotalDisplayFare'])) {
                     $newFare = (float)$preBookResponse['Price']['TotalDisplayFare'];
                     if (abs($newFare - $finalTotalFare) > 0.01) {
                        Log::warning("Price changed during PreBook (Display)! Old: $finalTotalFare, New: $newFare. Updating payload.");
                        $finalTotalFare = $newFare;
                    }
                }

                Log::info("PreBook Success. BookingRef: {$tboBookingReferenceId}, FinalPrice: {$finalTotalFare}");
            } else {
                $errorMsg = $preBookResponse['Status']['Description'] ?? 'PreBook failed';
                Log::error("TBO PreBook Failed for {$booking->booking_reference}: {$errorMsg}");

                // If session expired during PreBook, we can't continue
                $booking->update([
                    'booking_status' => BookingStatus::FAILED,
                    'tbo_response' => $preBookResponse,
                ]);
                $this->triggerRefund($booking);
                return false;
            }

            // 3. Prepare Guest Details based on TBO requirements
            $nameParts = explode(' ', trim($booking->guest_name));
            $firstName = $nameParts[0];
            $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'Guest';

            // Construct TBO Payload according to documentation and common TBO requirements
            $tboPayload = [
                'BookingCode' => $finalBookingCode,
                'CustomerDetails' => [
                    [
                        'CustomerNames' => [
                            [
                                'Title' => 'Mr', 
                                'FirstName' => $firstName,
                                'LastName' => $lastName,
                                'Type' => 'Adult'
                            ]
                        ]
                    ]
                ],
                'ClientReferenceId' => $booking->booking_reference,
                'BookingReferenceId' => $tboBookingReferenceId,
                'TotalFare' => $finalTotalFare, // Use the potentially updated price
                'EmailId' => $booking->guest_email,
                'PhoneNumber' => str_replace(['+', ' '], '', \App\Helpers\CountryHelper::getDialCode($booking->phone_country_code) . $booking->guest_phone),
                'BookingType' => 'Voucher',
                'PaymentMode' => 'Limit'
            ];

            try {
                // 4. Final Booking
                Log::info("Calling TBO Book API for {$booking->booking_reference}");
                $tboResponse = $this->tboService->book($tboPayload);
                Log::info("TBO Book Response for {$booking->booking_reference}", ['response' => $tboResponse]);

                if (isset($tboResponse['Status']['Code']) && $tboResponse['Status']['Code'] == 200) {
                    // Success!
                    $booking->update([
                        'booking_status' => BookingStatus::CONFIRMED,
                        'tbo_booking_id' => $tboResponse['BookingCode'] ?? $tboResponse['BookingId'] ?? null,
                        'confirmation_number' => $tboResponse['ConfirmationNo'] ?? $tboResponse['ConfirmationNumber'] ?? null,
                        'tbo_response' => $tboResponse,
                    ]);
                    
                    // Clear hotel search caches to ensure fresh availability data
                    // This is critical: TBO has now reduced inventory, so cached searches are stale
                    Cache::flush();
                    Log::info("Search cache cleared after booking confirmation for {$booking->booking_reference}");
                    
                    Log::info("Booking CONFIRMED for {$booking->booking_reference}");
                    return true;
                } else {
                    // TBO Rejection
                    $errorMsg = $tboResponse['Status']['Description'] ?? 'Unknown TBO error';
                    Log::error("TBO Booking Failed for {$booking->booking_reference}: {$errorMsg}");

                    $booking->update([
                        'booking_status' => BookingStatus::FAILED,
                        'tbo_response' => $tboResponse,
                    ]);

                    // Trigger refund logic
                    $this->triggerRefund($booking);
                    return false;
                }

            } catch (\Exception $e) {
                Log::error("Exception during TBO booking for {$booking->booking_reference}: " . $e->getMessage());
                Log::error($e->getTraceAsString());
                
                $booking->update([
                    'booking_status' => BookingStatus::FAILED,
                ]);

                $this->triggerRefund($booking);
                return false;
            }
        });
    }

    /**
     * Cancel the booking (typically on payment failure)
     */
    public function cancelBooking(HotelBooking $booking, string $reason): void
    {
        $booking->update([
            'booking_status' => BookingStatus::CANCELLED,
            'payment_status' => PaymentStatus::FAILED,
            'payment_details' => array_merge($booking->payment_details ?? [], ['failure_reason' => $reason]),
        ]);
        
        Log::info("Booking {$booking->booking_reference} marked as CANCELLED due to: {$reason}");
    }

    /**
     * Logic to trigger a refund (Stub for now)
     */
    protected function triggerRefund(HotelBooking $booking)
    {
        Log::warning("REFUND TRIGGERED for booking {$booking->booking_reference}. Amount: {$booking->total_price}");
        // Integrate with APS Refund API here
    }
}
