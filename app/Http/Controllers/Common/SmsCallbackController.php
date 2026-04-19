<?php

namespace App\Http\Controllers\Common;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\SmsCallbackService;

class SmsCallbackController extends Controller
{
    protected SmsCallbackService $smsCallbackService;

    public function __construct(SmsCallbackService $smsCallbackService)
    {
        $this->smsCallbackService = $smsCallbackService;
    }

    /**
     * Handle sms callback
     *
     * @param Request $request
     * @param string $provider_id
     * @return string
     * @throws Exception
     */
    public function handleCallback(Request $request, string $provider_id): string
    {
        return $this->smsCallbackService->handleCallback($request, (int)$provider_id);
    }
}
