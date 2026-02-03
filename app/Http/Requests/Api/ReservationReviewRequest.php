<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReservationReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'hotel_id' => ['required', 'string'],
            'booking_code' => ['required', 'string'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'rooms' => ['required', 'integer', 'min:1'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'discount_code' => ['nullable', 'string', 'max:255'],
        ];

        // If user is not authenticated, require name, email, and phone
        // If authenticated, these are optional (will use user's data if not provided)
        if (! auth('sanctum')->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
            $rules['phone_country_code'] = ['required', 'string', 'max:10'];
            $rules['phone'] = [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $countryCode = strtolower(str_replace('+', '', $this->phone_country_code));
                    $lengths = [
                        '966' => 9,  // SA
                        '20' => 11,  // EG
                        '1' => 10,   // US
                        '971' => 9,  // AE
                        '965' => 8,  // KW
                        '973' => 8,  // BH
                        '974' => 8,  // QA
                        '968' => 8,  // OM
                        '962' => 9,  // JO
                        '961' => 8,  // LB
                    ];

                    $expectedLength = $lengths[$countryCode] ?? null;
                    $digitsOnly = preg_replace('/[^0-9]/', '', $value);

                    if ($expectedLength && strlen($digitsOnly) !== $expectedLength) {
                        $fail(__('The phone number must be exactly :length digits for the selected country.', ['length' => $expectedLength]));
                    }
                },
            ];
        } else {
            // For authenticated users, these fields are optional
            $rules['name'] = ['nullable', 'string', 'max:255'];
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
            $rules['phone_country_code'] = ['nullable', 'string', 'max:10'];
            $rules['phone'] = [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (! $value) {
                        return;
                    }
                    $countryCode = strtolower(str_replace('+', '', $this->phone_country_code));
                    $lengths = [
                        '966' => 9,  // SA
                        '20' => 11,  // EG
                        '1' => 10,   // US
                        '971' => 9,  // AE
                        '965' => 8,  // KW
                        '973' => 8,  // BH
                        '974' => 8,  // QA
                        '968' => 8,  // OM
                        '962' => 9,  // JO
                        '961' => 8,  // LB
                    ];

                    $expectedLength = $lengths[$countryCode] ?? null;
                    $digitsOnly = preg_replace('/[^0-9]/', '', $value);

                    if ($expectedLength && strlen($digitsOnly) !== $expectedLength) {
                        $fail(__('The phone number must be exactly :length digits for the selected country.', ['length' => $expectedLength]));
                    }
                },
            ];
        }

        $rules['notes'] = ['nullable', 'string', 'max:1000'];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('Hotel ID is required.'),
            'booking_code.required' => __('Booking code is required.'),
            'check_in.required' => __('Check-in date is required.'),
            'check_in.date' => __('Check-in must be a valid date.'),
            'check_out.required' => __('Check-out date is required.'),
            'check_out.date' => __('Check-out must be a valid date.'),
            'check_out.after' => __('Check-out date must be after check-in date.'),
            'name.required' => __('Name is required.'),
            'email.required' => __('Email is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'phone.required' => __('Phone number is required.'),
            'phone_country_code.required' => __('Country code is required.'),
        ];
    }
}
