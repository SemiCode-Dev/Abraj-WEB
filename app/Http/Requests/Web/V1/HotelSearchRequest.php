<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class HotelSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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
