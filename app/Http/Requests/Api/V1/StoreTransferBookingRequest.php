<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTransferBookingRequest extends FormRequest
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
            'transfer_date' => ['required', 'date'],
            'transfer_time' => ['required', 'date_format:H:i:s,H:i'], // Adjust format if needed
            'trip_type' => ['required', 'in:go,go_and_back'],
            'return_date' => ['nullable', 'required_if:trip_type,go_and_back', 'date', 'after_or_equal:transfer_date'],
            'return_time' => ['nullable', 'required_if:trip_type,go_and_back', 'date_format:H:i:s,H:i'],
            'passengers' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
