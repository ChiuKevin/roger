<?php

namespace App\Services\Common;

use App\Models\User;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class BaseUserService extends Service
{
    public function getProfile(): JsonResponse
    {
        $user_info = auth()->user()->only(['id', 'username', 'nickname', 'country_code', 'phone', 'email', 'image']);

        return $this->success($user_info);
    }

    public function getProfileBrief(): JsonResponse
    {
        $user_info = auth()->user()->only(['id', 'nickname', 'image']);

        return $this->success($user_info);
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return JsonResponse
     */
    public function updateProfile(User $user, array $data): JsonResponse
    {
        if (isset($data['phone'])) {
            $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);
            $check = $this->findByCountryCodeAndPhone($data['country_code'], $data['phone']);
            if (!empty($check) && $check->id != $user->id) {
                return $this->error(__('validation.unique', ['attribute' => 'phone']));
            }
            //若有修改電話，需要通過簡訊驗證碼
            if (!$this->verifySmsCode($data)) {
                return $this->error(__('auth.sms'));
            }
        }

        $user->update($data);

        return $this->success();
    }

    /**
     * Set user's password
     *
     * @param User $user
     * @param array $data
     * @return JsonResponse
     */
    public function setPassword(User $user, array $data): JsonResponse
    {
        if (!empty($user->password) && !Hash::check($data['password'], $user->password)) {
            return $this->error(__('auth.password'));
        }

        $update['password'] = $data['new_password'];

        return $this->updateProfile($user, $update);
    }

    public function updateNotificationSetting(User $user, array $data): JsonResponse
    {
        $user->notificationSetting()->update($data);

        return $this->success();
    }

    /**
     * Remove current user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function removeUser(User $user): JsonResponse
    {
        $user->delete();

        return $this->success();
    }

    /**
     * Find user by country code and phone.
     *
     * @param string $country_code
     * @param string $phone
     * @return User|null
     */
    public function findByCountryCodeAndPhone(string $country_code, string $phone): ?User
    {
        return User::where('country_code', $country_code)
            ->where('phone', $phone)
            ->first();
    }

    /**
     * Verify SMS Code.
     *
     * @param array $data
     * @return bool
     */
    public function verifySmsCode(array $data): bool
    {
        $cache_key = 'sms_code:' . $data['phone'];
        $verification_data = Cache::get($cache_key);

        if (isset($verification_data) && $data['sms_code'] === $verification_data['verification_code']) {
            Cache::forget($cache_key);
            return true;
        }

        return false;
    }
}
