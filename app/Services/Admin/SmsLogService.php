<?php

namespace App\Services\Admin;

use App\Models\SmsLog;
use App\Services\Service;
use Illuminate\Http\JsonResponse;

class SmsLogService extends Service
{
    /**
     * Get Sms Logs.
     *
     * @param array $data
     * @return JsonResponse
     */
    function getSmsLogs(array $data): JsonResponse
    {
        $query = SmsLog::query();

        if (!empty($data['country_code'])) {
            $query->where('country_code', $data['country_code']);
        }

        if (!empty($data['phone'])) {
            $query->where('phone', $data['phone']);
        }

        $sms_logs = $query->get();

        return $this->successList($sms_logs);
    }
}