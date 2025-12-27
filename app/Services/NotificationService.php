<?php

namespace App\Services;

use App\Helpers\CountryHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class NotificationService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send OTP via email or SMS based on identifier type
     *
     * @param string $identifier Email or phone number
     * @param string $otp OTP code
     * @param string|null $countryCode Country code for phone normalization (e.g., '966', 'SA')
     * @return array Result with status and message
     */
    public function sendOtp(string $identifier, string $otp, ?string $countryCode = null): array
    {
        $type = $this->detectIdentifierType($identifier);

        if ($type === 'email') {
            return $this->sendOtpViaEmail($identifier, $otp);
        } else {
            return $this->sendOtpViaSms($identifier, $otp, $countryCode);
        }
    }

    /**
     * Detect if identifier is email or phone number
     *
     * @param string $identifier
     * @return string 'email' or 'phone'
     */
    public function detectIdentifierType(string $identifier): string
    {
        // Check if it's a valid email format
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Otherwise treat as phone number
        return 'phone';
    }

    /**
     * Send OTP via email
     *
     * @param string $email
     * @param string $otp
     * @return array
     */
    protected function sendOtpViaEmail(string $email, string $otp): array
    {
        try {
            Mail::to($email)->send(new OtpMail($otp));

            Log::info('OTP email sent', ['email' => $email, 'otp' => $otp]);

            return [
                'status' => 'success',
                'message' => 'OTP sent to your email',
                'channel' => 'email',
            ];
        } catch (\Exception $e) {
            // Log the error with OTP for development/debugging
            Log::error('Failed to send OTP email', [
                'email' => $email,
                'otp' => $otp,
                'error' => $e->getMessage(),
            ]);

            // For development: In production, return success but log the OTP
            // This allows the system to work even if email is misconfigured
            if (config('app.env') === 'local') {
                Log::warning('OTP Email Failed - Check logs for OTP', [
                    'email' => $email,
                    'otp' => $otp,
                    'error_hint' => 'Check MAIL_* configuration in .env',
                ]);
                
                return [
                    'status' => 'success',
                    'message' => 'OTP generated (check logs - email config issue)',
                    'channel' => 'email',
                    'dev_note' => 'Email not sent. OTP logged. Check storage/logs/laravel.log',
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to send OTP email: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send OTP via SMS
     *
     * @param string $phone
     * @param string $otp
     * @param string|null $countryCode
     * @return array
     */
    protected function sendOtpViaSms(string $phone, string $otp, ?string $countryCode = null): array
    {
        // If phone already has +, use as is
        // Otherwise combine country code + phone
        if (!str_starts_with($phone, '+')) {
            // Remove leading 0 if present
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            
            // Get country code (default to +20 for Egypt)
            if (!$countryCode) {
                $countryCode = '+20';
            } elseif (!str_starts_with($countryCode, '+')) {
                $countryCode = '+' . $countryCode;
            }
            
            // Combine: +20 + 1141367100 = +201141367100
            $normalizedPhone = $countryCode . $phone;
        } else {
            $normalizedPhone = $phone;
        }

        // Create SMS message in Arabic and English
        $message = "Your OTP code is: {$otp}\nرمز التحقق الخاص بك: {$otp}";

        $result = $this->smsService->send($normalizedPhone, $message);

        if ($result['status'] === 'success') {
            Log::info('OTP SMS sent', ['phone' => $normalizedPhone]);
            
            return [
                'status' => 'success',
                'message' => 'OTP sent to your phone',
                'channel' => 'sms',
            ];
        }

        return $result;
    }

    /**
     * Normalize phone number to E.164 format
     *
     * @param string $phone Raw phone number
     * @param string|null $countryCode Country code (e.g., '966', 'SA')
     * @return string Normalized phone in E.164 format (e.g., +966501234567)
     */
    public function normalizePhoneNumber(string $phone, ?string $countryCode = null): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // If already in E.164 format (starts with +), return as is
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Get dial code from country code or use default (Egypt)
        // Changed default to '20' for Egyptian numbers like 01141367100
        $dialCode = CountryHelper::getDialCode($countryCode, '20');

        // If phone starts with the dial code, add + prefix
        if (str_starts_with($phone, $dialCode)) {
            return '+' . $phone;
        }

        // If phone starts with 0, remove it (common in local formats)
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        // Prepend country dial code
        return '+' . $dialCode . $phone;
    }
}
