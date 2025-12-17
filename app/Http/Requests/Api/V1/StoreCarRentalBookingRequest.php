<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCarRentalBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRequired = Auth::guard('sanctum')->check() ? 'nullable' : 'required';

        return [
            'name' => [$isRequired, 'string', 'max:255'],
            'email' => [$isRequired, 'email', 'max:255'],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'phone' => [$isRequired, 'string', 'max:255'],
            'destination_country_id' => ['nullable', 'exists:countries,id'],
            'destination_city_id' => ['nullable', 'exists:cities,id'],
            'pickup_date' => ['required', 'date'],
            'pickup_time' => ['required', 'date_format:H:i:s,H:i'],
            'return_date' => ['required', 'date', 'after_or_equal:pickup_date'],
            'return_time' => ['required', 'date_format:H:i:s,H:i'],
            'drivers' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
