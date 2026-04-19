<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Rules\CountryCodePhoneRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseUserController extends Controller
{
    protected $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        return $this->userService->getProfile();
    }

    /**
     * Get the brief authenticated User Info.
     *
     * @return JsonResponse
     */
    public function getProfileBrief(): JsonResponse
    {
        return $this->userService->getProfileBrief();
    }

    /**
     * Set current user's profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setProfile(Request $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validate([
            'username'     => 'required|string|max:60',
            'nickname'     => 'required|string|max:60',
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
            'sms_code'     => 'required_with:phone|regex:/^[0-9]{6}$/',
            'email'        => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'image'        => 'required|nullable|string',
        ]);

        return $this->userService->updateProfile($user, $data);
    }

    /**
     * Set current user's password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setPassword(Request $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validate([
            'password'     => 'sometimes|string|between:6,60',
            'new_password' => 'required|string|between:6,60|confirmed',
        ]);

        return $this->userService->setPassword($user, $data);
    }

    /**
     * Get current user's notification setting.
     *
     * @return JsonResponse
     */
    public function getNotificationSetting(): JsonResponse
    {
        return $this->userService->getNotificationSetting();
    }

    /**
     * Set current user's notification setting.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setNotificationSetting(Request $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validate([
            'email_new_quote'         => 'sometimes|boolean',
            'email_quote_updated'     => 'sometimes|boolean',
            'email_new_message'       => 'sometimes|boolean',
            'email_info'              => 'sometimes|boolean',
            'push_new_quote'          => 'sometimes|boolean',
            'push_quote_updated'      => 'sometimes|boolean',
            'push_new_message'        => 'sometimes|boolean',
            'push_system'             => 'sometimes|boolean',
            'sms_quote_updated'       => 'sometimes|boolean',
            'sms_booking_success'     => 'sometimes|boolean',
            'pro_email_credit_refund' => 'sometimes|boolean',
            'pro_email_new_request'   => 'sometimes|boolean',
            'pro_email_quote_updated' => 'sometimes|boolean',
            'pro_email_quote_viewed'  => 'sometimes|boolean',
        ]);

        return $this->userService->updateNotificationSetting($user, $data);
    }

    /**
     * Remove current user
     *
     * @return JsonResponse
     */
    public function remove(): JsonResponse
    {
        $user = auth()->user();

        return $this->userService->removeUser($user);
    }
}
