<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use App\Services\Common\SmsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send sms.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function sendSms(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
        ]);

        return $this->smsService->sendSms($data);
    }
}
