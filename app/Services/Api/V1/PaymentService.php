<?php

namespace App\Services\Api\V1;

class PaymentService
{
    public $APS_MERCHANT_ID;

    public $APS_ACCESS_CODE;

    public $APS_SHA_REQUEST;

    public $APS_SHA_RESPONSE;

    public function __construct()
    {
        $this->APS_MERCHANT_ID = config('services.aps.merchant_id');
        $this->APS_ACCESS_CODE = config('services.aps.access_code');
        $this->APS_SHA_REQUEST = config('services.aps.sha_request');
        $this->APS_SHA_RESPONSE = config('services.aps.sha_response');
    }

    public function apsPayment()
    {
        $data = [
            'command' => 'PURCHASE',
            'access_code' => $this->APS_ACCESS_CODE,
            'merchant_identifier' => $this->APS_MERCHANT_ID,
            'merchant_reference' => uniqid('order_'),
            'amount' => 200,
            'currency' => 'USD',
            'language' => app()->getLocale(),
            'customer_email' => 'test@example.com',
            'return_url' => route('aps.callback'),
        ];

        $data['signature'] = $this->apsSignature($data, $this->APS_SHA_REQUEST);

        return $data;
    }

    public function apsCallback($data)
    {
        \Log::info('APS Callback Received', ['data' => $data]);

        $receivedSignature = $data['signature'] ?? null;
        unset($data['signature']);

        $generatedSignature = $this->apsSignature($data, $this->APS_SHA_RESPONSE);

        if ($receivedSignature !== $generatedSignature) {
            \Log::error('APS Callback Signature Mismatch', [
                'received' => $receivedSignature,
                'generated' => $generatedSignature,
            ]);

            return 'Invalid signature — payment not trusted';
        }

        $merchantReference = $data['merchant_reference'] ?? '';
        $status = $data['status'] ?? '';

        // Handle Hotel Bookings
        if (str_starts_with(strtoupper($merchantReference), 'BK-')) {
            $booking = \App\Models\HotelBooking::where('booking_reference', $merchantReference)->first();

            if (! $booking) {
                \Log::error('Booking not found for Reference: '.$merchantReference);

                return redirect()->route('home')->with('error', __('Booking not found.'));
            }

            // Re-login user if session was lost (e.g. due to SameSite cookie policy on POST callback)
            // This ensures the user is logged in whether payment succeeded or failed
            if (! auth()->check() && $booking->user_id) {
                auth()->loginUsingId($booking->user_id);
            }

            if ($status == '14') { // Success
                $bookingService = app(\App\Services\Api\V1\BookingService::class);
                $success = $bookingService->completeBooking($booking, $data);

                if ($success) {
                    return redirect()->route('home')->with('success', __('Payment successful! Your booking has been confirmed.'));
                } else {
                    return redirect()->route('home')->with('error', __('Payment successful, but room booking failed. Our team will contact you for a refund.'));
                }
            } else { // Failure
                $bookingService = app(\App\Services\Api\V1\BookingService::class);
                $bookingService->cancelBooking($booking, $data['response_message'] ?? 'Payment failed');

                return redirect()->route('home')->with('error', __('Payment failed: ').($data['response_message'] ?? 'Unknown error'));
            }
        }

        // Default behavior for other types of payments
        if ($status == '14') {
            session()->flash('success', 'Payment Successful: Order '.$merchantReference);

            return redirect()->route('home');
        }

        session()->flash('error', 'Payment Failed: '.($data['response_message'] ?? ''));

        return redirect()->route('home');
    }

    public function apsSignature($data, $phrase)
    {
        ksort($data);
        $str = $phrase;

        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            } // Important for APS
            $str .= "$key=$value";
        }

        $str .= $phrase;

        return hash('sha256', $str);
    }

    public function apsPaymentForReservation(array $params = [])
    {
        $amount = $params['amount'] ?? 0;
        $currency = strtoupper($params['currency'] ?? 'USD');
        $customerEmail = $params['customer_email'] ?? '';
        $merchantReference = $params['merchant_reference'] ?? '';

        // APS requires amount in smallest unit.
        // SAR and USD both use 2 decimal places for APS processing.
        $amountInSmallestUnit = (int) round($amount * 100);

        $data = [
            'command' => 'PURCHASE',
            'access_code' => $this->APS_ACCESS_CODE,
            'merchant_identifier' => $this->APS_MERCHANT_ID,
            'merchant_reference' => $merchantReference,
            'amount' => $amountInSmallestUnit,
            'currency' => $currency,
            'language' => app()->getLocale(),
            'customer_email' => $customerEmail,
            'return_url' => route('aps.callback'),
        ];

        $data['signature'] = $this->apsSignature($data, $this->APS_SHA_REQUEST);

        return $data;
    }
}
