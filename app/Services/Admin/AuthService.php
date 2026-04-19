<?php

namespace App\Services\Admin;

use App\Services\Common\BaseAuthService;
use Illuminate\Http\JsonResponse;

class AuthService extends BaseAuthService
{
    protected RoleService $roleService;

    public function __construct(AdminUserService $adminUserService, RoleService $roleService)
    {
        parent::__construct($adminUserService);
        $this->roleService = $roleService;
    }

    public function getUserInfo(): JsonResponse
    {
        $user = auth('admin')->user();

        $role_ids = $user->roles->pluck('id')->toArray();
        $role_names = $user->roles->pluck('name')->toArray();

        $regionPermissions = $this->roleService->getPermissionsByRoleIds($role_ids);

        $data = [
            'id'                 => $user->id,
            'username'           => $user->username,
            'email'              => $user->email,
            'image'              => $user->image,
            'role_ids'           => $role_ids,
            'role_names'         => $role_names,
            'region_permissions' => $regionPermissions
        ];

        return $this->success($data);
    }
}
