<?php

namespace App\Services\Consumer;

use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemService extends Service
{
    /**
     * Get consumer maintenance status
     *
     * @return JsonResponse
     */
    public function getMaintenanceStatus(): JsonResponse
    {
        return $this->success(['is_under_maintenance' => false, 'time' => now()]);
    }

    /**
     * Get consumer constants version
     *
     * @return JsonResponse
     */
    public function getConstantsVersion(): JsonResponse
    {
        return $this->success(['version' => config('constants.CONSUMER.VERSION')]);
    }

    /**
     * Get consumer app version
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppVersion(Request $request): JsonResponse
    {
        $platform = $request->query('platform');

        if (!$platform || !in_array($platform, ['ios', 'android'])) {
            return $this->error(__('auth.platform'));
        }

        $platform = strtoupper($platform);
        $appVersion = config("constants.CONSUMER.APP_VERSION.{$platform}");

        return $this->success(['app_version' => $appVersion]);
    }

    /**
     * Get consumer app market
     *
     * @return JsonResponse
     */
    public function getAppMarket(): JsonResponse
    {
        $appMarket = config("constants.CONSUMER.APP_MARKET");
        $appMarket = array_change_key_case($appMarket, CASE_LOWER);

        return $this->success($appMarket);
    }

    /**
     * Get consumer frontend constants
     *
     * @return JsonResponse
     */
    public function getFrontendConstants(): JsonResponse
    {
        return $this->success(config('constants.CONSUMER.FRONTEND'));
    }
}
