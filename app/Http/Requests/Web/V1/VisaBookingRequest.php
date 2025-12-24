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
            'duration' => ['required', 'integer', 'min:1', 'max:365'],
        ];

        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['phone_country_code'] = ['required', 'string', 'max:10'];
            $rules['phone'] = ['required', 'string', 'max:11'];
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
