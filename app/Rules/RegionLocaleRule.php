<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RegionLocaleRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch ($attribute) {
            case 'region':
                if (!in_array($value, ['hk', 'mo', 'tw'])) {
                    $fail(__('validation.in'));
                }
                break;
            case 'locale':
                if (!in_array($value, ['zh_hk', 'zh_mo', 'zh_tw', 'en'])) {
                    $fail(__('validation.in'));
                }
                break;
        }
    }
}
