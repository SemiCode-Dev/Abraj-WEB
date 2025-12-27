<?php

// Test OTP functionality
// Run with: php artisan tinker < test_otp.php

use App\Models\User;
use App\Services\Api\V1\AuthService;
use App\Services\NotificationService;
use App\Services\SmsService;

echo "\n=== Testing OTP Functionality ===\n\n";

// Test 1: Find user by email
echo "Test 1: Finding user by email...\n";
$user = User::where('email', 'ab2429501@gmail.com')->first();
if ($user) {
    echo "✅ User found: {$user->name} (ID: {$user->id})\n";
    echo "   Email: {$user->email}\n";
    echo "   Phone: {$user->phone}\n";
    echo "   Country Code: {$user->phone_country_code}\n\n";
} else {
    echo "❌ User not found\n\n";
}

// Test 2: Find user by phone
echo "Test 2: Finding user by phone (1141367100)...\n";
$user2 = User::where('phone', '1141367100')->first();
if ($user2) {
    echo "✅ User found: {$user2->name} (ID: {$user2->id})\n\n";
} else {
    echo "❌ User not found with phone 1141367100\n\n";
}

// Test 3: Check SMS config
echo "Test 3: Checking SMS configuration...\n";
$smsEnabled = config('services.sms.enabled');
$smsProvider = config('services.sms.provider');
echo "SMS Enabled: " . ($smsEnabled ? 'YES' : 'NO') . "\n";
echo "SMS Provider: {$smsProvider}\n\n";

// Test 4: Check Mail config  
echo "Test 4: Checking Email configuration...\n";
$mailDriver = config('mail.default');
$mailHost = config('mail.mailers.smtp.host');
echo "Mail Driver: {$mailDriver}\n";
echo "Mail Host: {$mailHost}\n\n";

// Test 5: Test NotificationService identifier detection
echo "Test 5: Testing identifier detection...\n";
$notificationService = app(NotificationService::class);
echo "Email identifier type: " . $notificationService->detectIdentifierType('ab2429501@gmail.com') . "\n";
echo "Phone identifier type: " . $notificationService->detectIdentifierType('01141367100') . "\n\n";

// Test 6: Test phone normalization
echo "Test 6: Testing phone normalization...\n";
echo "01141367100 → " . $notificationService->normalizePhoneNumber('01141367100', '20') . "\n";
echo "1141367100 → " . $notificationService->normalizePhoneNumber('1141367100', '20') . "\n";
echo "+201141367100 → " . $notificationService->normalizePhoneNumber('+201141367100', '20') . "\n\n";

echo "=== Tests Complete ===\n";
