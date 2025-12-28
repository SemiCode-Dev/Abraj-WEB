<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_country_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:11'],
            'destination_country_id' => ['required', 'exists:countries,id'],
            'destination_city_id' => ['required'],
            'transfer_date' => ['required', 'date', 'after_or_equal:today'],
            'transfer_time' => ['required'],
            'trip_type' => ['required', Rule::in(['go', 'go_and_back'])],
            'passengers' => ['required', 'integer', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->trip_type === 'go_and_back') {
            $rules['return_date'] = ['required', 'date', 'after:transfer_date'];
            $rules['return_time'] = ['required'];
        }

        if (auth()->check()) {
            unset($rules['name'], $rules['email'], $rules['phone'], $rules['phone_country_code']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'email.required' => __('Email is required.'),
            'email.email' => __('Email must be a valid email address.'),
            'phone.required' => __('Phone number is required.'),
            'phone_country_code.required' => __('Country code is required.'),
            'destination_country_id.required' => __('Destination country is required.'),
            'destination_country_id.exists' => __('Selected destination country is invalid.'),
            'destination_city_id.required' => __('Destination city is required.'),
            'destination_city_id.exists' => __('Selected destination city is invalid.'),
            'transfer_date.required' => __('Transfer date is required.'),
            'transfer_date.after_or_equal' => __('Transfer date must be today or later.'),
            'transfer_time.required' => __('Transfer time is required.'),
            'trip_type.required' => __('Trip type is required.'),
            'return_date.required' => __('Return date is required for round trip.'),
            'return_date.after' => __('Return date must be after transfer date.'),
            'return_time.required' => __('Return time is required for round trip.'),
            'passengers.required' => __('Number of passengers is required.'),
            'passengers.min' => __('At least one passenger is required.'),
        ];
    }
}
