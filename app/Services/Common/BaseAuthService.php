<?php

namespace App\Services\Common;

use App\Models\User;
use App\Services\Service;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class BaseAuthService extends Service
{
    protected $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Check if the phone is unique.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function checkPhone(array $data): JsonResponse
    {
        $data['country_code'] = $this->formatCountryCode($data['country_code']);//因GET方法會將加號轉義為空白
        $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);
        if (!empty($this->userService->findByCountryCodeAndPhone($data['country_code'], $data['phone']))) {
            return $this->error(__('validation.unique', ['attribute' => 'phone']));
        }

        return $this->success();
    }

    /**
     * Login by phone.
     *
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function loginByPhone(array $data): JsonResponse
    {
        $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);

        if (!$this->userService->verifySmsCode($data)) {
            return $this->error(__('auth.sms'));
        }

        $client = $this->getClientName();
        $pro_flag = $client == 'pro' ? 1 : 0;
        if (!$user = $this->userService->findByCountryCodeAndPhone($data['country_code'], $data['phone'])) {
            //使用者不存在，下一步要請求註冊API進行註冊。
            unset($data['sms_code']);
            $data['is_pro'] = $pro_flag;
            $user = User::create($data);
            $user->notificationSetting()->create();
            $resData = ['action' => 'registrations', 'new_user' => 'y', 'user_id' => $user->id];
        } elseif (empty($user->username) || empty($user->email)) {
            //使用者已存在，但資料不全，下一步要請求註冊API補完資料。
            $resData = ['action' => 'registrations', 'new_user' => 'n', 'user_id' => $user->id];
        } else {
            //使用者已存在，且資料齊全，直接登入。
            $token = auth()->login($user);
            throw_if(!$token, AuthenticationException::class);
            $resData['token'] = $token;
            $resData['expires_in'] = auth()->factory()->getTTL() * 60;

            if ($pro_flag) {
                if (!$user->is_pro) {
                    $user->update(['is_pro' => $pro_flag]);
                    $resData['new_pro'] = 'y';
                } else
                    $resData['new_pro'] = 'n';
            }
        }

        return $this->success($resData);
    }

    /**
     * Login by email.
     *
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function loginByEmail(array $data): JsonResponse
    {
        $client = $this->getClientName();
        $guard = $client == 'admin' ? 'admin' : 'api';
        $token = auth($guard)->attempt($data);
        throw_if(!$token, AuthenticationException::class);

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(array $data): JsonResponse
    {
        $data['phone'] = $this->formatPhone($data['country_code'], $data['phone']);

        $check = $this->userService->findByCountryCodeAndPhone($data['country_code'], $data['phone']);
        if (!empty($data['user_id'])) {
            if (!empty($check) && $check->id != $data['user_id']) {
                return $this->error(__('validation.unique', ['attribute' => 'phone']));
            }
            $user = User::find($data['user_id']);
            if (!$user) {
                return $this->error(__('error.404', ['attribute' => 'User']), 404);
            }
            if (!empty($user->username) && !empty($user->email)) {
                return $this->error(__('error.auth.user_fully_registered'));
            }
        } else {
            if (!empty($check)) {
                return $this->error(__('validation.unique', ['attribute' => 'phone']));
            }
            $user = new User;
        }

        $client = $this->getClientName();
        $pro_flag = $client == 'pro' ? 1 : 0;
        $attrs = [];
        if ($pro_flag) {
            if (!$user->is_pro || !$user->exists) {
                $data['is_pro'] = $pro_flag;
                $attrs['new_pro'] = 'y';
            } else
                $attrs['new_pro'] = 'n';
        }

        $data['nickname'] = $data['username'];
        $user->fill($data);
        $user->save();

        $token = auth()->login($user);
        throw_if(!$token, AuthenticationException::class);

        return $this->respondWithToken($token, $attrs);
    }

}
