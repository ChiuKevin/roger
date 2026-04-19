<?php

namespace App\Services\Common\Providers\Sms;

interface SmsProvider
{
    public function sendSms(string $country_code, string $phone, string $message): array;
}
