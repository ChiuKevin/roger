<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait FormatParameterTrait
{
    /**
     * Format country code
     *
     * @param string $country_code
     * @return string
     */
    protected function formatCountryCode(string $country_code): string
    {
        if ($country_code[0] != '+') {
            $country_code = '+' . $country_code;
        }

        return $country_code;
    }

    /**
     * Format Taiwan mobile phone number format, remove the first '0'
     *
     * @param string $country_code
     * @param string $phone
     * @return string
     */
    protected function formatPhone(string $country_code, string $phone): string
    {
        if ($country_code == '+886' && $phone[0] == '0') {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Get client name from route prefix
     *
     * @return string
     */
    protected function getClientName(): string
    {
        $route_prefix = Route::getCurrentRoute()->getPrefix();

        return explode('/', trim($route_prefix, '/'))[0];
    }
}
