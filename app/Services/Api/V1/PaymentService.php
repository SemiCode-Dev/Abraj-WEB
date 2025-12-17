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
        $receivedSignature = $data['signature'] ?? null;
        unset($data['signature']);

        $generatedSignature = $this->apsSignature($data, $this->APS_SHA_RESPONSE);

        if ($receivedSignature !== $generatedSignature) {
            return 'Invalid signature — payment not trusted';
        }

        if ($data['status'] == '14') {

            session()->flash('success', 'Payment Successful: Order '.$data['merchant_reference']);

            return redirect()->route('home');
        }

        session()->flash('error', 'Payment Failed: '.$data['response_message']);

        return redirect()->route('home');
    }

    public function apsSignature($data, $phrase)
    {
        ksort($data);
        $str = $phrase;

        foreach ($data as $key => $value) {
            $str .= "$key=$value";
        }

        $str .= $phrase;

        return hash('sha256', $str);
    }

    public function apsPaymentForReservation(array $params = [])
    {
        $amount = $params['amount'] ?? 200;
        $currency = $params['currency'] ?? 'USD';
        $customerEmail = $params['customer_email'] ?? 'test@example.com';
        $merchantReference = $params['merchant_reference'] ?? uniqid('order_');

        // Convert amount based on currency
        // SAR uses fils (1 SAR = 1000 fils), USD uses cents (1 USD = 100 cents)
        $amountInSmallestUnit = $currency === 'SAR'
            ? (int) ($amount * 1000) // Convert to fils
            : (int) ($amount * 100); // Convert to cents

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
