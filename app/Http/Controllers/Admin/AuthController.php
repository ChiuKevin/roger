<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\BaseAuthController;
use App\Services\Admin\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseAuthController
{
    public function __construct(AuthService $authService)
    {
        parent::__construct($authService);
    }

    /**
     * Get the authenticated user information.
     *
     * @return JsonResponse
     */
    public function userInfo(): JsonResponse
    {
        return $this->authService->getUserInfo();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('admin')->logout();

        return $this->success();
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

}
