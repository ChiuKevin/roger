<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get roles
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->roleService->getRoles();
    }

    /**
     * Create role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255|unique:roles,name',
            'is_disabled'     => 'required|boolean',
            'remark'          => 'present|nullable|string',
            'permissions'     => 'required|array',
            'permissions.*.*' => 'sometimes|nullable|array',
        ]);

        return $this->roleService->createRole($data);
    }

    /**
     * Get role
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->roleService->getRoleById($id);
    }

    /**
     * Update role
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255|unique:roles,name,' . $id,
            'is_disabled'     => 'required|boolean',
            'remark'          => 'present|nullable|string',
            'permissions'     => 'required|array',
            'permissions.*.*' => 'sometimes|nullable|array',
        ]);

        return $this->roleService->updateRole($data, $id);
    }

    /**
     * Delete role
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->roleService->deleteRole($id);
    }
}
