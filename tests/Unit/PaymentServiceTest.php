<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\HotelBooking;
use App\Constants\BookingStatus;
use App\Constants\PaymentStatus;
use App\Services\Api\V1\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
    }

    /**
     * Test APS signature generation
     */
    public function test_aps_signature_generation()
    {
        $data = [
            'command' => 'PURCHASE',
            'merchant_reference' => 'TEST123',
            'amount' => 10000,
            'currency' => 'SAR',
        ];

        $phrase = 'test_phrase';
        $signature = $this->paymentService->apsSignature($data, $phrase);

        // Signature should be a 64-character hex string (SHA256)
        $this->assertIsString($signature);
        $this->assertEquals(64, strlen($signature));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $signature);
    }

    /**
     * Test signature consistency
     */
    public function test_signature_is_consistent()
    {
        $data = [
            'merchant_reference' => 'TEST123',
            'amount' => 10000,
        ];

        $phrase = 'test_phrase';
        
        $signature1 = $this->paymentService->apsSignature($data, $phrase);
        $signature2 = $this->paymentService->apsSignature($data, $phrase);

        $this->assertEquals($signature1, $signature2);
    }

    /**
     * Test signature changes with different data
     */
    public function test_signature_changes_with_data()
    {
        $data1 = ['merchant_reference' => 'TEST123', 'amount' => 10000];
        $data2 = ['merchant_reference' => 'TEST456', 'amount' => 10000];

        $phrase = 'test_phrase';
        
        $signature1 = $this->paymentService->apsSignature($data1, $phrase);
        $signature2 = $this->paymentService->apsSignature($data2, $phrase);

        $this->assertNotEquals($signature1, $signature2);
    }

    /**
     * Test signature ignores null and empty values
     */
    public function test_signature_ignores_null_values()
    {
        $data1 = [
            'merchant_reference' => 'TEST123',
            'amount' => 10000,
            'optional_field' => null,
        ];

        $data2 = [
            'merchant_reference' => 'TEST123',
            'amount' => 10000,
        ];

        $phrase = 'test_phrase';
        
        $signature1 = $this->paymentService->apsSignature($data1, $phrase);
        $signature2 = $this->paymentService->apsSignature($data2, $phrase);

        $this->assertEquals($signature1, $signature2);
    }

    /**
     * Test payment data generation for reservation
     */
    public function test_payment_data_generation()
    {
        $params = [
            'amount' => 500.50,
            'currency' => 'SAR',
            'customer_email' => 'test@example.com',
            'merchant_reference' => 'BK-TEST123',
        ];

        $paymentData = $this->paymentService->apsPaymentForReservation($params);

        $this->assertIsArray($paymentData);
        $this->assertArrayHasKey('command', $paymentData);
        $this->assertArrayHasKey('merchant_reference', $paymentData);
        $this->assertArrayHasKey('amount', $paymentData);
        $this->assertArrayHasKey('currency', $paymentData);
        $this->assertArrayHasKey('signature', $paymentData);
        $this->assertArrayHasKey('return_url', $paymentData);

        $this->assertEquals('PURCHASE', $paymentData['command']);
        $this->assertEquals('BK-TEST123', $paymentData['merchant_reference']);
        $this->assertEquals(50050, $paymentData['amount']); // 500.50 * 100
        $this->assertEquals('SAR', $paymentData['currency']);
        $this->assertEquals('test@example.com', $paymentData['customer_email']);
    }

    /**
     * Test amount conversion to smallest unit
     */
    public function test_amount_conversion_to_smallest_unit()
    {
        $testCases = [
            ['input' => 100, 'expected' => 10000],
            ['input' => 100.50, 'expected' => 10050],
            ['input' => 0.99, 'expected' => 99],
            ['input' => 1234.56, 'expected' => 123456],
        ];

        foreach ($testCases as $case) {
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => $case['input'],
                'currency' => 'SAR',
                'customer_email' => 'test@example.com',
                'merchant_reference' => 'TEST',
            ]);

            $this->assertEquals(
                $case['expected'],
                $paymentData['amount'],
                "Amount {$case['input']} should convert to {$case['expected']}"
            );
        }
    }

    /**
     * Test currency uppercase conversion
     */
    public function test_currency_uppercase_conversion()
    {
        $currencies = ['sar', 'usd', 'eur', 'aed'];

        foreach ($currencies as $currency) {
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => 100,
                'currency' => $currency,
                'customer_email' => 'test@example.com',
                'merchant_reference' => 'TEST',
            ]);

            $this->assertEquals(strtoupper($currency), $paymentData['currency']);
        }
    }

    /**
     * Test default values in payment data
     */
    public function test_payment_data_defaults()
    {
        $paymentData = $this->paymentService->apsPaymentForReservation([]);

        $this->assertEquals(0, $paymentData['amount']);
        $this->assertEquals('USD', $paymentData['currency']);
        $this->assertEquals('', $paymentData['customer_email']);
        $this->assertEquals('', $paymentData['merchant_reference']);
    }

    /**
     * Test payment data includes required APS fields
     */
    public function test_payment_data_includes_required_fields()
    {
        $paymentData = $this->paymentService->apsPaymentForReservation([
            'amount' => 100,
            'currency' => 'SAR',
            'customer_email' => 'test@example.com',
            'merchant_reference' => 'TEST',
        ]);

        $requiredFields = [
            'command',
            'access_code',
            'merchant_identifier',
            'merchant_reference',
            'amount',
            'currency',
            'language',
            'customer_email',
            'return_url',
            'signature',
        ];

        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $paymentData, "Payment data should include $field");
        }
    }

    /**
     * Test signature verification logic
     */
    public function test_signature_verification()
    {
        $data = [
            'merchant_reference' => 'BK-TEST123',
            'amount' => 10000,
            'currency' => 'SAR',
            'status' => '14',
        ];

        $phrase = config('services.aps.sha_response');
        
        // Generate signature
        $signature = $this->paymentService->apsSignature($data, $phrase);
        
        // Add signature to data
        $dataWithSignature = array_merge($data, ['signature' => $signature]);
        
        // Extract signature
        $receivedSignature = $dataWithSignature['signature'];
        unset($dataWithSignature['signature']);
        
        // Regenerate signature
        $generatedSignature = $this->paymentService->apsSignature($dataWithSignature, $phrase);
        
        // Verify they match
        $this->assertEquals($receivedSignature, $generatedSignature);
    }

    /**
     * Test payment reference format
     */
    public function test_payment_reference_format()
    {
        $validReferences = [
            'BK-ABC1234567',
            'BK-XYZ9876543',
            'BK-TEST123456',
        ];

        foreach ($validReferences as $reference) {
            $paymentData = $this->paymentService->apsPaymentForReservation([
                'amount' => 100,
                'currency' => 'SAR',
                'customer_email' => 'test@example.com',
                'merchant_reference' => $reference,
            ]);

            $this->assertEquals($reference, $paymentData['merchant_reference']);
            $this->assertTrue(str_starts_with($reference, 'BK-'));
        }
    }
}
