<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_reference',
        'hotel_code',
        'hotel_name',
        'room_code',
        'room_name',
        'check_in',
        'check_out',
        'nights',
        'total_price',
        'currency',
        'guest_name',
        'guest_email',
        'guest_phone',
        'pax_details',
        'booking_status',
        'payment_status',
        'tbo_booking_id',
        'confirmation_number',
        'tbo_response',
        'payment_reference',
        'payment_details',
    ];

    protected $casts = [
        'tbo_response' => 'array',
        'payment_details' => 'array',
        'pax_details' => 'array',
        'check_in' => 'date',
        'check_out' => 'date',
    ];
}
