<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\BaseUserController;
use App\Rules\CountryCodePhoneRule;
use App\Services\Admin\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseUserController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

    /**
     * Get users
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->userService->getUsers();
    }

    /**
     * Create user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username'     => 'required|string|max:60',
            'nickname'     => 'sometimes|string|max:60',
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
            'email'        => 'required|email|unique:users',
            'image'        => 'sometimes|nullable|string',
            'remark'       => 'sometimes|nullable|string|max:100',
            'tags'         => 'present|nullable|array'
        ]);

        return $this->userService->createUser($data);
    }

    /**
     * Get user
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->userService->getUserById($id);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'username'     => 'required|string|max:60',
            'nickname'     => 'sometimes|string|max:60',
            'country_code' => ['required', new CountryCodePhoneRule()],
            'phone'        => ['required', new CountryCodePhoneRule()],
            'email'        => 'required|email|unique:users,email,' . $id,
            'image'        => 'sometimes|nullable|string',
            'remark'       => 'sometimes|nullable|string|max:100',
            'is_pro'       => 'required|boolean',
            'is_disabled'  => 'required|boolean',
            'tags'         => 'present|nullable|array'
        ]);

        return $this->userService->updateUser($data, $id);
    }

    /**
     * Delete user
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->userService->deleteUser($id);
    }
}
