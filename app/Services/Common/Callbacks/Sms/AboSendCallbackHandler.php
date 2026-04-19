<?php

namespace App\Services\Common\Callbacks\Sms;

use App\Models\SmsLog;
use Exception;
use Illuminate\Http\Request;

class AboSendCallbackHandler implements SmsCallbackHandler
{
    public function getResponse(): string
    {
        return '';
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        $response_data = $request->all();
        $send_code = $response_data['sendCode'] ?? null;
        $response_status = $response_data['state'] ?? null;

        if (is_null($send_code) || !isset($response_status)) {
            throw new Exception(__('error.callback.missing_parameters'));
        }

        $sign = strtoupper(md5(config('abosend.org_code') . $send_code . $response_status . $response_data['mobile'] . config('abosend.md5_key')));
        if ($sign !== $response_data['sign']) {
            throw new Exception(__('error.callback.invalid_signature'));
        }

        $sms_log = SMSLog::where('batch_id', $send_code)->first();
        if (!empty($sms_log)) {
            if ($sms_log->status == SmsLog::STATUS_SENT) {
                $status = match ($response_status) {
                    '16', '64' => SMSLog::STATUS_SUCCESS,//16:接收成功。64:已讀。
                    default => SMSLog::STATUS_FAILED,//其他代碼:接收失敗。
                };
                $sms_log->update([
                    'status'            => $status,
                    'callback_response' => json_encode($response_data, JSON_UNESCAPED_UNICODE)
                ]);
            }
        } else
            throw new Exception(__('error.callback.order_not_found', ['attribute' => $send_code]));
    }
}
