<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class FlightBookingRequest extends FormRequest
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
            'origin_country_id' => ['required', 'exists:countries,id'],
            'origin_city_id' => ['required', 'exists:cities,id'],
            'destination_country_id' => ['required', 'exists:countries,id'],
            'destination_city_id' => ['required', 'exists:cities,id'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['required', 'integer', 'min:0', 'max:20'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:departure_date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

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
            'origin_country_id.required' => __('Origin country is required.'),
            'origin_country_id.exists' => __('Selected origin country is invalid.'),
            'origin_city_id.required' => __('Origin city is required.'),
            'origin_city_id.exists' => __('Selected origin city is invalid.'),
            'destination_country_id.required' => __('Destination country is required.'),
            'destination_country_id.exists' => __('Selected destination country is invalid.'),
            'destination_city_id.required' => __('Destination city is required.'),
            'destination_city_id.exists' => __('Selected destination city is invalid.'),
            'adults.required' => __('Number of adults is required.'),
            'adults.min' => __('At least one adult is required.'),
            'children.required' => __('Number of children is required.'),
            'children.min' => __('Number of children cannot be negative.'),
            'departure_date.required' => __('Departure date is required.'),
            'departure_date.after_or_equal' => __('Departure date must be today or later.'),
            'return_date.required' => __('Return date is required.'),
            'return_date.after' => __('Return date must be after departure date.'),
        ];
    }
}
