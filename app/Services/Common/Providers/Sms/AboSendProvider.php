<?php

namespace App\Services\Common\Providers\Sms;

use App\Models\SmsLog;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AboSendProvider implements SmsProvider
{
    public function sendSms(string $country_code, string $phone, string $message): array
    {
        $sms_data = [
            'orgCode'    => config('abosend.org_code'),
            'mobileArea' => '+0',
            'mobiles'    => $country_code . $phone,
            'content'    => $message,
            'rand'       => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'notifyUrl'  => route('callbacks.sms', ['provider_id' => SmsLog::PROVIDER_ABOSEND])
        ];
        $sms_data['sign'] = strtoupper(md5($sms_data['orgCode'] . $sms_data['content'] . $sms_data['rand'] . config('abosend.md5_key')));
        $sms_data['content'] = urlencode(urlencode($sms_data['content']));

        $url = 'http://smsapi.abosend.com:8205/api/sendSMS';

        $client = new Client();
        try {
            $response_raw = $client->post($url, [
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => $sms_data,
            ]);
            $response = json_decode($response_raw->getBody(), true);
            $status = $response['code'] == 200 ? SmsLog::STATUS_SENT : SmsLog::STATUS_NOT_SENT;

            return [
                'provider'      => SmsLog::PROVIDER_ABOSEND,
                'batch_id'      => $response['data']['sendCode'] ?? null,
                'send_response' => json_encode($response, JSON_UNESCAPED_UNICODE),
                'status'        => $status
            ];
        } catch (GuzzleException $e) {
            return [
                'provider'      => SmsLog::PROVIDER_ABOSEND,
                'send_response' => $e->getMessage(),
                'status'        => SmsLog::STATUS_NOT_SENT
            ];
        } catch (Exception $e) {
            return [
                'provider'      => SmsLog::PROVIDER_ABOSEND,
                'send_response' => $e->getMessage(),
                'status'        => SmsLog::STATUS_NOT_SENT
            ];
        }
    }
}
