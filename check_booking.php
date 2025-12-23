<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bookings = \App\Models\HotelBooking::where('hotel_code', '1491912')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get()
    ->map(function($b){
        return [
            'id' => $b->id,
            'ref' => $b->booking_reference,
            'status' => $b->booking_status, // This determines if we filter it
            'payment' => $b->payment_status,
            'check_in' => $b->check_in,
            'check_out' => $b->check_out,
            'room' => $b->room_name,
            'tbo_response' => $b->tbo_response // To see why it was refused
        ];
    });

print_r($bookings->toArray());
