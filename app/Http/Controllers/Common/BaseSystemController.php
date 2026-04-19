<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseSystemController extends Controller
{
    protected $systemService;

    public function __construct($systemService)
    {
        $this->systemService = $systemService;
    }

    /**
     * Get maintenance status
     *
     * @return JsonResponse
     */
    public function getMaintenanceStatus(): JsonResponse
    {
        return $this->systemService->getMaintenanceStatus();
    }

    /**
     * Get constants version
     *
     * @return JsonResponse
     */
    public function getConstantsVersion(): JsonResponse
    {
        return $this->systemService->getConstantsVersion();
    }

    /**
     * Get app version
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppVersion(Request $request): JsonResponse
    {
        return $this->systemService->getAppVersion($request);
    }

    /**
     * Get app market
     *
     * @return JsonResponse
     */
    public function getAppMarket(): JsonResponse
    {
        return $this->systemService->getAppMarket();
    }

    /**
     * Get frontend constants
     *
     * @return JsonResponse
     */
    public function getFrontendConstants(): JsonResponse
    {
        return $this->systemService->getFrontendConstants();
    }
}
