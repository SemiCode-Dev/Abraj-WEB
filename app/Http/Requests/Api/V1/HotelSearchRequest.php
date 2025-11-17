<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class HotelSearchRequest extends FormRequest
{
    public function rules()
    {
        return [
            'CheckIn' => 'required|date',
            'CheckOut' => 'required|date',
            'HotelCodes' => 'required',
            'GuestNationality' => 'required|string',
            'PaxRooms' => 'required|array',
        ];
    }
}
