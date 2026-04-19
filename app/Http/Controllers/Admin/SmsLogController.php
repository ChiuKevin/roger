<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use App\Services\Admin\SmsLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    protected SmsLogService $smsLogService;

    public function __construct(SmsLogService $smsLogService)
    {
        $this->smsLogService = $smsLogService;
    }

    /**
     * Get Sms Logs.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get sms logs by country code & phone
     *
     * @param Request $request
     * @return JsonResponse .
     */
    public function filter(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()]
        ]);

        return $this->smsLogService->getSmsLogs($data);
    }

}
