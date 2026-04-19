<?php

namespace App\Services\Common\Callbacks\Sms;

use App\Models\SmsLog;
use Exception;

class SmsCallbackHandlerFactory
{
    /**
     * @param int $provider_id
     * @return SmsCallbackHandler
     * @throws Exception
     */
    public static function getHandler(int $provider_id): SmsCallbackHandler
    {
        return match ($provider_id) {
            SMSLog::PROVIDER_EVERY8D => new Every8dCallbackHandler(),
            SMSLog::PROVIDER_ABOSEND => new AboSendCallbackHandler(),
            default => throw new Exception("No handler found for provider_id: $provider_id")
        };
    }
}
