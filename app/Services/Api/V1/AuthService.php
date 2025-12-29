<?php

namespace App\Services\Api\V1;

use App\Models\Otp;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return [
                'status' => 'error',
                'message' => 'Invalid credentials',
            ];
        }
        auth()->login($user);

        return [
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $user,
        ];
    }

    public function register($data)
    {
        // Normalize phone number
        $phone = $data['phone'];
        $countryCode = $data['phone_country_code'] ?? '20'; // Default to Egypt
        
        // Remove any non-numeric characters from phone
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading 0 if present
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        
        // Ensure country code has + prefix
        if (!str_starts_with($countryCode, '+')) {
            $countryCode = '+' . $countryCode;
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $phone, // Clean number: 1141367100
            'phone_country_code' => $countryCode, // With +: +20
        ]);

        // Login user for session-based auth (Web)
        auth()->login($user);

        // Create token for API use
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => $user,
            'token' => $token,
        ];
    }
    public function logout($user)
    {
        // Revoke the user's current token
        $user->currentAccessToken()->delete();

        return [
            'status' => 'success',
            'message' => 'Logged out successfully',
        ];
    }

    public function sendOtp($identifier)
    {
        // Normalize phone number if it's not an email
        $normalizedIdentifier = $this->normalizeIdentifier($identifier);

        // Check if user exists (email or phone)
        $user = User::where('email', $identifier)
            ->orWhere('email', $normalizedIdentifier)
            ->orWhere('phone', $identifier)
            ->orWhere('phone', $normalizedIdentifier)
            ->first();

        if (! $user) {
            return [
                'status' => 'error',
                'message' => 'Invalid email or phone number',
            ];
        }

        // Generate random 4 digit OTP
        $token = rand(1000, 9999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Create or update OTP record, reset attempts and verified flag
        Otp::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token, 'expires_at' => $expiresAt, 'attempts' => 0, 'verified' => false]
        );

        // Send OTP via email or SMS based on identifier type
        $result = $this->notificationService->sendOtp(
            $identifier,
            $token,
            $user->phone_country_code
        );

        if ($result['status'] === 'error') {
            return $result;
        }

        return [
            'status' => 'success',
            'message' => $result['message'] ?? 'OTP sent successfully',
            'channel' => $result['channel'] ?? 'unknown',
        ];
    }

    public function verifyOtp($identifier, $token)
    {
        // Normalize phone number if it's not an email
        $normalizedIdentifier = $this->normalizeIdentifier($identifier);

        // Find user first
        $user = User::where('email', $identifier)
            ->orWhere('email', $normalizedIdentifier)
            ->orWhere('phone', $identifier)
            ->orWhere('phone', $normalizedIdentifier)
            ->first();

        if (! $user) {
            return [
                'status' => 'error',
                'message' => 'Invalid email or phone number',
            ];
        }

        $otpRecord = Otp::where('user_id', $user->id)->first();

        if (! $otpRecord) {
            return [
                'status' => 'error',
                'message' => 'Invalid OTP',
            ];
        }

        // Check if OTP has already been verified (one-time use enforcement)
        if ($otpRecord->verified) {
            return [
                'status' => 'error',
                'message' => 'OTP has already been used. Please request a new OTP.',
            ];
        }

        if ($otpRecord->attempts >= 6) {
            return [
                'status' => 'error',
                'message' => 'Too many attempts. Please request a new OTP.',
            ];
        }

        if ($otpRecord->token != $token) {
            $otpRecord->increment('attempts');

            return [
                'status' => 'error',
                'message' => 'Invalid OTP',
            ];
        }

        if (Carbon::now()->greaterThanOrEqualTo($otpRecord->expires_at)) {
            return [
                'status' => 'error',
                'message' => 'OTP expired',
            ];
        }

        // Mark OTP as verified (one-time use)
        $otpRecord->update(['verified' => true]);

        return [
            'status' => 'success',
            'message' => 'OTP verified successfully',
        ];
    }

    public function resetPassword($email, $token, $newPassword)
    {
        // Normalize identifier (can be email or phone)
        $normalizedIdentifier = $this->normalizeIdentifier($email);

        $user = User::where('email', $email)
            ->orWhere('email', $normalizedIdentifier)
            ->orWhere('phone', $email)
            ->orWhere('phone', $normalizedIdentifier)
            ->first();

        if (! $user) {
            return [
                'status' => 'error',
                'message' => 'User not found',
            ];
        }

        $otpRecord = Otp::where('user_id', $user->id)->first();

        // Check verification status
        if (! $otpRecord || ! $otpRecord->verified) {
            return [
                'status' => 'error',
                'message' => 'OTP has not been verified',
            ];
        }

        // Check token match just in case, though verified flag implies it was correct.
        if ($otpRecord->token != $token) {
            return [
                'status' => 'error',
                'message' => 'Invalid OTP token',
            ];
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        // Clear OTP after use
        Otp::where('user_id', $user->id)->delete();

        // Revoke all tokens (logout)
        $user->tokens()->delete();

        return [
            'status' => 'success',
            'message' => 'Password reset successfully',
        ];
    }

    /**
     * Normalize identifier for database lookup
     * Removes leading 0 from phone numbers
     *
     * @param string $identifier
     * @return string
     */
    private function normalizeIdentifier(string $identifier): string
    {
        // If it's an email, return as is
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $identifier;
        }

        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $identifier);

        // If it starts with +, return as is (international format)
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Remove leading 0 (common in local formats like 01141367100)
        if (str_starts_with($phone, '0')) {
            return substr($phone, 1);
        }

        return $phone;
    }
}
