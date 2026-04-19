<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected AdminUserService $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    /**
     * Get admin users
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->adminUserService->getAdminUsers();
    }

    /**
     * Create admin user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username'    => 'required|unique:admin_users,username',
            'email'       => 'required|email|unique:admin_users,email',
            'remark'      => 'sometimes|nullable|string',
            'is_disabled' => 'required|boolean',
            'role_ids'    => 'present|nullable|array',
            'role_ids.*'  => 'sometimes|integer'
        ]);

        return $this->adminUserService->create($data);
    }

    /**
     * Get admin user
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->adminUserService->getById($id);
    }

    /**
     * Update admin user
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'username'     => 'sometimes|unique:admin_users,username,' . $id,
            'new_password' => 'sometimes|string',
            'image'        => 'sometimes|nullable|string',
            'remark'       => 'sometimes|nullable|string',
            'is_disabled'  => 'sometimes|boolean',
            'role_ids'     => 'sometimes|nullable|array',
            'role_ids.*'   => 'sometimes|integer'
        ]);

        return $this->adminUserService->update($data, $id);
    }

    /**
     * Delete admin user
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->adminUserService->delete($id);
    }
}
