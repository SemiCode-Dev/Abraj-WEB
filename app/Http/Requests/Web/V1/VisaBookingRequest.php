<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class VisaBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'visa_type' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'nationality_id' => ['required', 'exists:countries,id'],
            'duration' => ['required', 'integer', 'min:1', 'max:365'],
        ];

        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
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
                }
            ];
        } else {
            $rules['passport_number'] = ['required', 'string', 'max:50'];
        }

        $rules['comment'] = ['nullable', 'string', 'max:1000'];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'phone.required' => __('Phone number is required.'),
            'phone_country_code.required' => __('Country code is required.'),
            'visa_type.required' => __('Visa type is required.'),
            'country_id.required' => __('Country is required.'),
            'country_id.exists' => __('Selected country is invalid.'),
            'duration.required' => __('Duration is required.'),
            'duration.min' => __('Duration must be at least 1 day.'),
            'duration.max' => __('Duration cannot exceed 365 days.'),
            'passport_number.required' => __('Passport number is required.'),
        ];
    }
}
