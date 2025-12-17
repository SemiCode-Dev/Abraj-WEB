<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFlightBookingRequest extends FormRequest
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
            'origin_country_id' => ['nullable', 'exists:countries,id'],
            'origin_city_id' => ['nullable', 'exists:cities,id'],
            'destination_country_id' => ['nullable', 'exists:countries,id'],
            'destination_city_id' => ['nullable', 'exists:cities,id'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'departure_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after_or_equal:departure_date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
