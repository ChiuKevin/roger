<?php

namespace App\Services\Admin;

use App\Models\AdminUser;
use App\Models\Role;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminUserService extends Service
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    const DEFAULT_PASSWORD = '12345678';

    /**
     * Get admin users
     *
     * @return JsonResponse
     */
    public function getAdminUsers(): JsonResponse
    {
        $users = AdminUser::with('roles')
            ->where('id', '!=', 1)
            ->get()
            ->map(function ($user) {
                return [
                    'id'          => $user->id,
                    'username'    => $user->username,
                    'email'       => $user->email,
                    'image'       => $user->image,
                    'remark'      => $user->remark,
                    'is_disabled' => $user->is_disabled,
                    'created_at'  => $user->created_at,
                    'updated_at'  => $user->updated_at,
                    'role_ids'    => $user->roles->pluck('id')->all(),
                    'role_names'  => $user->roles->pluck('name')->all()
                ];
            });

        return $this->successList($users);
    }

    /**
     * Create a new admin user.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        $data['password'] = Hash::make(self::DEFAULT_PASSWORD);

        $adminUser = new AdminUser($data);
        $adminUser->save();

        if (isset($data['role_ids'])) {
            foreach ($data['role_ids'] as $roleId) {
                $role = Role::findById($roleId);
                $adminUser->assignRole($role);
            }
        }

        return $this->success();
    }

    /**
     * Get an admin user by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getById(string $id): JsonResponse
    {
        $user = AdminUser::findOrFail($id);
        $user->load('roles');
        $roles = $user->roles->pluck('id')->all();

        $data = [
            'id'          => $user->id,
            'username'    => $user->username,
            'email'       => $user->email,
            'image'       => $user->image,
            'remark'      => $user->remark,
            'is_disabled' => $user->is_disabled,
            'created_at'  => $user->created_at,
            'updated_at'  => $user->updated_at,
            'roles'       => $roles,
        ];

        return $this->success($data);
    }

    /**
     * Update an existing admin user.
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function update(array $data, string $id): JsonResponse
    {
        $adminUser = AdminUser::findOrFail($id);
        if (isset($data['new_password'])) {
            $data['password'] = base64_decode($data['new_password']);
            unset($data['new_password']);
        }
        $adminUser->update($data);

        if (isset($data['role_ids'])) {
            $adminUser->roles()->detach();

            foreach ($data['role_ids'] as $roleId) {
                $role = Role::findById($roleId);
                $adminUser->assignRole($role);
            }
        }

        return $this->success();
    }

    /**
     * Delete an admin user by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $user = AdminUser::findOrFail($id);
        $user->delete();

        return $this->success();
    }
}
