<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CountryCodePhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $country_code = request('country_code');

        switch ($attribute) {
            case 'country_code':
                if (!in_array($value, ['+852', '+853', '+86', '+886'])) {
                    $fail(__('validation.in'));
                }
                break;
            case 'phone':
                if (!$this->validatePhone($country_code, $value)) {
                    $fail(__('validation.regex'));
                }
                break;
        }
    }

    private function validatePhone(string $country_code, string $phone): bool
    {
        switch ($country_code) {
            case '+852':
            case '+853':
                return preg_match('/^\d{8}$/', $phone);
            case '+86':
                return preg_match('/^1\d{10}$/', $phone);
            case '+886':
                return preg_match('/^0?9\d{8}$/', $phone);
            default:
                return true;
        }
    }
}
