<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = \App\Models\HotelBooking::where('hotel_code', '1491912')
    ->where('check_in', '2026-01-09')
    ->orderBy('created_at', 'desc')
    ->first();

if ($booking) {
    echo "ID: " . $booking->id . "\n";
    echo "Ref: " . $booking->booking_reference . "\n";
    echo "Status: " . $booking->booking_status . "\n"; // CRITICAL
    echo "Payment: " . $booking->payment_status . "\n";
    echo "TBO Response: " . json_encode($booking->tbo_response) . "\n";
} else {
    echo "No booking found for this hotel/date.\n";
}
