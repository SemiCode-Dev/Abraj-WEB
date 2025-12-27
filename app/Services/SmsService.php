<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    protected $provider;
    protected $enabled;
    protected $from;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'log');
        $this->enabled = config('services.sms.enabled', false);
        $this->from = config('services.sms.from');
    }

    /**
     * Send SMS message to a phone number
     *
     * @param string $phoneNumber Phone number in E.164 format (e.g., +966501234567)
     * @param string $message Message to send
     * @return array Result with status and message
     */
    public function send(string $phoneNumber, string $message): array
    {
        // If SMS is disabled, log the message instead
        if (!$this->enabled) {
            Log::info('SMS (disabled - logging only)', [
                'to' => $phoneNumber,
                'message' => $message,
            ]);

            return [
                'status' => 'success',
                'message' => 'SMS logged (SMS disabled in config)',
            ];
        }

        try {
            // Route to appropriate provider
            return match ($this->provider) {
                'twilio' => $this->sendViaTwilio($phoneNumber, $message),
                'vonage' => $this->sendViaVonage($phoneNumber, $message),
                'unifonic' => $this->sendViaUnifonic($phoneNumber, $message),
                default => $this->logOnly($phoneNumber, $message),
            };
        } catch (Exception $e) {
            Log::error('SMS sending failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Failed to send SMS: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendViaTwilio(string $phoneNumber, string $message): array
    {
        $sid = config('services.sms.twilio.sid');
        $token = config('services.sms.twilio.token');
        $from = config('services.sms.from');
        
        // Check if Twilio SDK is installed
        if (!class_exists('\Twilio\Rest\Client')) {
            Log::error('Twilio SDK not installed. Run: composer require twilio/sdk');
            return $this->logOnly($phoneNumber, $message);
        }
        
        try {
            $client = new \Twilio\Rest\Client($sid, $token);
            
            $client->messages->create($phoneNumber, [
                'from' => $from,
                'body' => $message
            ]);
            
            Log::info('SMS sent via Twilio', ['phone' => $phoneNumber]);
            
            return [
                'status' => 'success',
                'message' => 'SMS sent successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Twilio SMS failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Failed to send SMS: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS via Vonage (Nexmo)
     */
    protected function sendViaVonage(string $phoneNumber, string $message): array
    {
        // TODO: Implement Vonage integration when credentials are provided
        // Requires: composer require vonage/client
        
        Log::warning('Vonage SMS provider not yet configured, logging instead', [
            'to' => $phoneNumber,
            'message' => $message,
        ]);

        return [
            'status' => 'success',
            'message' => 'SMS logged (Vonage not configured)',
        ];
    }

    /**
     * Send SMS via Unifonic
     */
    protected function sendViaUnifonic(string $phoneNumber, string $message): array
    {
        // TODO: Implement Unifonic integration when credentials are provided
        
        Log::warning('Unifonic SMS provider not yet configured, logging instead', [
            'to' => $phoneNumber,
            'message' => $message,
        ]);

        return [
            'status' => 'success',
            'message' => 'SMS logged (Unifonic not configured)',
        ];
    }

    /**
     * Log SMS instead of sending (fallback)
     */
    protected function logOnly(string $phoneNumber, string $message): array
    {
        Log::info('SMS (log only)', [
            'to' => $phoneNumber,
            'message' => $message,
        ]);

        return [
            'status' => 'success',
            'message' => 'SMS logged',
        ];
    }
}
