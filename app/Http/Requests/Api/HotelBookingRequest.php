<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class HotelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|string',
            'hotel_name' => 'nullable|string',
            'booking_code' => 'required|string',
            'room_name' => 'nullable|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'phone_country_code' => 'nullable|string|max:10',
            'discount_code' => 'nullable|string',
        ];
    }
}
