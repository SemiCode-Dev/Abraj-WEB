<?php

use Illuminate\Support\Facades\Route;
use App\Models\Otp;
use App\Models\User;

// Temporary route to get OTP for testing (REMOVE IN PRODUCTION)
Route::get('/get-otp/{identifier}', function($identifier) {
    // Find user
    $user = \App\Models\User::where('email', $identifier)
        ->orWhere('phone', $identifier)
        ->first();
    
    if (!$user) {
        return response()->json([
            'error' => 'User not found',
            'identifier' => $identifier
        ], 404);
    }
    
    // Get OTP
    $otp = \App\Models\Otp::where('user_id', $user->id)->first();
    
    if (!$otp) {
        return response()->json([
            'error' => 'No OTP found for this user',
            'hint' => 'Request OTP first via /api/v1/auth/send-otp'
        ], 404);
    }
    
    return response()->json([
        'user_email' => $user->email,
        'user_phone' => $user->phone,
        'otp' => $otp->token,
        'expires_at' => $otp->expires_at,
        'attempts' => $otp->attempts,
        'verified' => $otp->verified,
        'note' => 'For development only - remove this route in production'
    ]);
});
