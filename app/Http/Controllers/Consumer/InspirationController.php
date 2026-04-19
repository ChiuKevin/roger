<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use App\Services\Consumer\InspirationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InspirationController extends Controller
{

    protected InspirationService $inspirationService;

    public function __construct(InspirationService $inspirationService)
    {
        $this->inspirationService = $inspirationService;
    }


    /**
     * Inspiration estimator.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function estimator(Request $request): JsonResponse
    {
        $data = $request->validate([
            'area_size'    => 'required|numeric',
            'nick_name'    => 'sometimes|string',
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
        ]);

        return $this->inspirationService->estimator($data);
    }
}
