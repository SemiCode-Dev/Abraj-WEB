<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber implements Rule
{
    protected ?string $countryCode;
    protected ?string $errorMessage = null;

    /**
     * Create a new rule instance.
     *
     * @param string|null $countryCode Default country code (e.g., 'SA', 'EG', 'US')
     */
    public function __construct(?string $countryCode = null)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true; // Let 'required' rule handle empty values
        }

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            // Parse the phone number
            $phoneNumber = $phoneUtil->parse($value, $this->countryCode);

            // Check if it's a valid number
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                $this->errorMessage = __('validation.phone_invalid');
                return false;
            }

            // Check if it's a possible number (length check)
            if (!$phoneUtil->isPossibleNumber($phoneNumber)) {
                $this->errorMessage = __('validation.phone_invalid_length');
                return false;
            }

            return true;
        } catch (NumberParseException $e) {
            $this->errorMessage = __('validation.phone_invalid_format');
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->errorMessage ?? __('validation.phone_invalid');
    }
}
