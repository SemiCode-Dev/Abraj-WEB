<?php

namespace App\Services\Api\V1;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        auth()->login($user);

        return [
            'status' => 'success',
            'message' => 'Register successful',
            'data' => $user,
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
        // Check if user exists (email or phone)
        $user = User::where('email', $identifier)->orWhere('phone', $identifier)->first();

        if (! $user) {
            return [
                'status' => 'error',
                'message' => 'Invalid email or phone number',
            ];
        }

        // Generate random 4 digit OTP
        $token = rand(1000, 9999);
        $expiresAt = Carbon::now()->addMinutes(10);

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token, 'expires_at' => $expiresAt, 'attempts' => 0]
        );

        // TODO: Integrate SMS/Email provider to actually send the OTP

        return [
            'status' => 'success',
            'message' => 'OTP sent successfully',
        ];
    }

    public function verifyOtp($identifier, $token)
    {
        // Find user first
        $user = User::where('email', $identifier)->orWhere('phone', $identifier)->first();

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

        // Mark OTP as verified
        $otpRecord->update(['verified' => true]);

        return [
            'status' => 'success',
            'message' => 'OTP verified successfully',
        ];
    }

    public function resetPassword($email, $token, $newPassword)
    {
        $user = User::where('email', $email)->first();
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
}
