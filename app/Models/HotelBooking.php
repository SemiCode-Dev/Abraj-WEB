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
        'hotel_name_ar',
        'hotel_name_en',
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
        'phone_country_code',
        'pax_details',
        'booking_status',
        'payment_status',
        'discount_code_id',
        'original_price',
        'discount_amount',
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
