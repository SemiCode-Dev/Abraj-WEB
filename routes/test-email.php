<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

Route::get('/test-email', function () {
    try {
        // Get current mail configuration
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
        ];
        
        Log::info('Mail Configuration', $config);
        
        // Send test email
        Mail::raw('Test email from Laravel. If you receive this, email is working!', function ($message) {
            $message->to(config('mail.mailers.smtp.username') ?: 'test@example.com')
                    ->subject('Test Email - Laravel OTP System');
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent! Check your inbox.',
            'config' => $config,
        ]);
        
    } catch (\Exception $e) {
        Log::error('Test email failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send email',
            'error' => $e->getMessage(),
            'config' => $config ?? [],
        ], 500);
    }
});
