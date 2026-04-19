<?php

namespace App\Services\Common\Callbacks\Sms;

use App\Models\SmsLog;
use Exception;
use Illuminate\Http\Request;

class Every8dCallbackHandler implements SmsCallbackHandler
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
        $batch_id = $response_data['BatchID'] ?? null;
        $response_status = $response_data['STATUS'] ?? null;

        if (is_null($batch_id) || !isset($response_status)) {
            throw new Exception(__('error.callback.missing_parameters'));
        }

        $sms_log = SMSLog::where('batch_id', $batch_id)->first();
        if (!empty($sms_log)) {
            if ($sms_log->status == SmsLog::STATUS_SENT) {
                $status = match ($response_status) {
                    '100', '999' => SMSLog::STATUS_SUCCESS,//100:已成功送達手機。999:回覆簡訊。
                    '0', '300', '700' => SMSLog::STATUS_SENT,//0:訊息已成功送達電信端，等待手機收訊中。300:預約簡訊。700:已傳送。
                    default => SMSLog::STATUS_FAILED,//其他代碼:傳送失敗。
                };
                $sms_log->update([
                    'status'            => $status,
                    'callback_response' => json_encode($response_data, JSON_UNESCAPED_UNICODE)
                ]);
            }
        } else
            throw new Exception(__('error.callback.order_not_found', ['attribute' => $batch_id]));
    }
}
