<?php

namespace App\Services\Common;

use App\Services\Common\Callbacks\Sms\SmsCallbackHandlerFactory;
use App\Services\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsCallbackService extends Service
{
    /**
     * @param Request $request
     * @param int $provider_id
     * @return string
     * @throws Exception
     */
    public function handleCallback(Request $request, int $provider_id): string
    {
        $handler = SmsCallbackHandlerFactory::getHandler($provider_id);
        try {
            $handler->handle($request);

            Log::info('Sms callback successfully:', [
                'source'       => $request->ip(),
                'provider_id'  => $provider_id,
                'request_data' => $request->all(),
            ]);
        } catch (Exception $e) {
            Log::error('Sms callback failed:', [
                'source'       => $request->ip(),
                'provider_id'  => $provider_id,
                'request_data' => $request->all(),
                'error'        => $e->getMessage(),
            ]);
        }

        return $handler->getResponse();
    }
}
