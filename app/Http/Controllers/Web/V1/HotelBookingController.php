<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\BookingService;
use App\Services\Api\V1\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HotelBookingController extends Controller
{
    protected $bookingService;
    protected $paymentService;

    public function __construct(BookingService $bookingService, PaymentService $paymentService)
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
    }

    /**
     * Store a pending booking and return payment data
     */
    public function store(Request $request)
    {
        Log::info('Hotel Booking Store Request received', $request->all());
        try {
            $validated = $request->validate([
                'hotel_code' => 'required|string',
                'hotel_name' => 'nullable|string',
                'room_code' => 'required|string',
                'room_name' => 'nullable|string',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'total_price' => 'required|numeric',
                'currency' => 'required|string',
                'guest_name' => 'required|string',
                'guest_email' => 'required|email',
                'guest_phone' => 'nullable|string',
            ]);

            Log::info('Hotel Booking Validation passed');

            // 1. Create the record in our database with status PENDING
            $booking = $this->bookingService->initiateBooking($validated);
            Log::info('Hotel Booking initiated in DB', ['reference' => $booking->booking_reference]);

            // 2. Generate payment data for Amazon Payment Services (APS)
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $booking->total_price,
                'currency' => $booking->currency,
                'customer_email' => $booking->guest_email,
                'merchant_reference' => $booking->booking_reference,
            ]);

            return response()->json([
                'success' => true,
                'payment_data' => $paymentData,
                'payment_url' => config('services.aps.payment_url'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Hotel Booking Validation Failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => __('Validation failed.'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Hotel Booking Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Check if it's a known availability error from BookingService
            $message = $e->getMessage();
            if (str_contains($message, 'availability check failed') || str_contains($message, 'Room is no longer available')) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400); // Bad Request (Client needs to refresh)
            }

            return response()->json([
                'success' => false,
                'message' => __('Failed to initiate booking. Please try again.'),
            ], 500);
        }
    }
}
