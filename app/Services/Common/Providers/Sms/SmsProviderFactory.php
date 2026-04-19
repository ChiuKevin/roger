<?php

namespace App\Services\Common\Providers\Sms;

class SmsProviderFactory
{
    public static function getProvider(string $country_code): SmsProvider
    {
        return match ($country_code) {
            '+886' => new Every8dProvider(),
            default => new AboSendProvider(),
        };
    }
}
