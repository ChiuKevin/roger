<?php

namespace App\Services\Common\Providers\Sms;

use App\Models\SmsLog;
use Every8d\Laravel\Facade\Every8d;
use Every8d\Message\SMS;
use Exception;

class Every8dProvider implements SmsProvider
{
    public function sendSms(string $country_code, string $phone, string $message): array
    {
        try {
            $sms = new SMS($country_code . $phone, $message);
            $response = Every8d::sendSms($sms);

            return [
                'provider'      => SmsLog::PROVIDER_EVERY8D,
                'batch_id'      => $response['BatchID'] ?? null,
                'send_response' => json_encode($response, JSON_UNESCAPED_UNICODE),
                'status'        => SmsLog::STATUS_SENT
            ];
        } catch (Exception $e) {
            return [
                'provider'      => SmsLog::PROVIDER_EVERY8D,
                'send_response' => $e->getMessage(),
                'status'        => SmsLog::STATUS_NOT_SENT,
            ];
        }
    }
}

