<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use App\Rules\RegionLocaleRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseAuthController extends Controller
{
    protected $authService;

    public function __construct($authService)
    {
        $this->authService = $authService;
    }

    /**
     * Check if the phone is unique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPhone(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()]
        ]);

        return $this->authService->checkPhone($data);
    }

    /**
     * Check if the email is unique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users'
        ]);

        return $this->success();
    }

    /**
     * Login by phone.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loginByPhone(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
            'sms_code'     => 'required|regex:/^[0-9]{6}$/',
        ]);

        return $this->authService->loginByPhone($data);
    }

    /**
     * Login by email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loginByEmail(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|between:6,60',
        ]);

        return $this->authService->loginByEmail($data);
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'      => 'sometimes|required|exists:users,id',
            'username'     => 'required',
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
            'email'        => 'required|email|unique:users,email,' . $request['user_id']
        ]);

        return $this->authService->register($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->success();
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

}
