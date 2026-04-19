<?php

namespace App\Services\Common;

use App\Models\SmsLog;
use App\Services\Common\Providers\Sms\SmsProviderFactory;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmsService extends Service
{
    const MAX_RETRIES          = 3;//最大重試次數，目前為3次
    const RETRY_SECONDS        = 10800;//重試時間，目前為3小時
    const MIN_INTERVAL_SECONDS = 60;//最短發送間隔，目前為60秒

    /**
     * Send sms.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function sendSms(array $data): JsonResponse
    {
        $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);

        $cache_key = 'sms_code:' . $data['phone'];
        $cache_data = Cache::get($cache_key, [
            'verification_code' => 0,
            'sent_codes'        => [],
            'sent_counts'       => 0,
            'last_sent_time'    => 0,
        ]);

        $is_debug = config('app.debug');
        $last_sent_time = $cache_data['last_sent_time'];
        $current_time = now()->timestamp;
        if (!$is_debug) {
            if ($current_time - $last_sent_time < self::RETRY_SECONDS && $cache_data['sent_counts'] >= self::MAX_RETRIES) {
                return $this->error(__('error.sms.too_many_retries'), 429);
            }
            if ($current_time - $last_sent_time < self::MIN_INTERVAL_SECONDS) {
                return $this->error(__('error.sms.wait_before_retry', ['seconds' => self::MIN_INTERVAL_SECONDS]), 429);
            }
            do {
                $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (in_array($verification_code, $cache_data['sent_codes']));

            $data['sms_message'] = "<Roger!> Your code is $verification_code, please do not leak it.";
            $provider = SmsProviderFactory::getProvider($data['country_code']);
            $response = $provider->sendSms($data['country_code'], $data['phone'], $data['sms_message']);
        } else {
            $verification_code = '000000';
            $response = [
                'provider'      => '0',
                'batch_id'      => 'TEST_' . date('YmdHis'),
                'send_response' => 'Debug mode, SMS not actually sent.',
                'status'        => SmsLog::STATUS_SENT
            ];
        }
        $log_data = [
            'provider'      => $response['provider'],
            'country_code'  => $data['country_code'],
            'phone'         => $data['phone'],
            'sms_code'      => $verification_code,
            'batch_id'      => $response['batch_id'] ?? null,
            'send_response' => $response['send_response'],
            'status'        => $response['status']
        ];
        SmsLog::create($log_data);

        if ($response['status'] == SmsLog::STATUS_SENT) {
            $cache_data['verification_code'] = $verification_code;
            $cache_data['sent_codes'][] = $verification_code;
            $cache_data['sent_counts']++;
            $cache_data['last_sent_time'] = $current_time;
            Cache::put($cache_key, $cache_data, now()->addMinutes(self::RETRY_SECONDS / 60));
        } else {
            Log::error($response['send_response']);
            return $this->error($response['send_response']);
        }

        return $this->success();
    }
}
