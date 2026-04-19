<?php

namespace App\Services\Admin;

use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService extends Service
{
    protected array $actionMap = [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ];

    protected array $featurePermissionMap = [
        'user-list'  => 'user',
        'order-list' => 'order'
    ];

    /**
     * Get roles.
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::where('name', '!=', 'admin-super')->get();

        return $this->successList($roles);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createRole(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {

            $permissionsToSync = $this->getPermissionsToSync($data['permissions']);

            $role = Role::create([
                'name'        => $data['name'],
                'guard_name'  => 'admin',
                'is_disabled' => $data['is_disabled'],
                'remark'      => $data['remark'] ?? null
            ]);

            $role->syncPermissions($permissionsToSync);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create role', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'role']), 500);
        }
    }

    /**
     * Get a role by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getRoleById(string $id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);
        $regionPermissions = $this->getPermissionsByRoleIds([$role->id]);

        $permissions = [];
        foreach ($regionPermissions as $regionPermission) {
            $permissions[$regionPermission['region']] = $regionPermission['permissions'];
        }

        $data = [
            'id'          => $role->id,
            'name'        => $role->name,
            'is_disabled' => $role->is_disabled ?? 0,
            'remark'      => $role->remark,
            'permissions' => $permissions
        ];

        return $this->success($data);
    }

    /**
     * Update a role.
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateRole(array $data, string $id): JsonResponse
    {
        DB::beginTransaction();

        try {

            $role = Role::findOrFail($id);

            if (isset($data['permissions'])) {
                $permissionsToSync = $this->getPermissionsToSync($data['permissions']);
                $role->syncPermissions($permissionsToSync);
            }

            $role->update([
                'name'        => $data['name'] ?? $role->name,
                'is_disabled' => $data['is_disabled'] ?? $role->is_disabled,
                'remark'      => $data['remark'] ?? $role->remark
            ]);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update role', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'role']), 500);
        }
    }

    /**
     * Process region permissions array.
     *
     * @param array $permissions
     * @return array
     */
    protected function getPermissionsToSync(array $permissions): array
    {
        $features = Feature::all()->keyBy('id');
        $permissionsToSync = [];

        foreach ($permissions as $region => $permission) {
            $permissionNames = [];

            foreach ($permission as $featureId => $actions) {
                if (!isset($features[$featureId])) continue;

                $actions[] = 'r';

                foreach ($actions as $action) {
                    if (!isset($this->actionMap[$action])) continue;

                    $featureName = $features[$featureId]->name;
                    $featureName = $this->featurePermissionMap[$featureName] ?? $featureName;
                    $permissionName = "{$this->actionMap[$action]}-{$featureName}";
                    $permissionNames[] = $permissionName;
                }
            }

            $ids = Permission::whereIn('name', $permissionNames)
                ->where('guard_name', 'admin')
                ->where('region', $region)
                ->pluck('id')
                ->toArray();

            $permissionsToSync = array_merge($permissionsToSync, $ids);
        }

        return $permissionsToSync;
    }

    /**
     * Get permissions by role IDs.
     *
     * @param array $ids
     * @return array
     */
    public function getPermissionsByRoleIds(array $ids): array
    {
        $roles = Role::with('permissions')->whereIn('id', $ids)->get();
        $features = Feature::whereNotNull('parent_id')->get()->keyBy('name');
        $permissions = [];
        $isSuperAdmin = false;

        foreach ($roles as $role) {
            if ($role->name == 'admin-super') {
                $isSuperAdmin = true;
            }

            foreach ($role->permissions as $permission) {
                $permissionParts = explode("-", $permission->name);
                $actionPart = $permissionParts[0];
                $featureName = implode("-", array_slice($permissionParts, 1));
                $featureName = array_search($featureName, $this->featurePermissionMap) ?: $featureName;

                if (!isset($features[$featureName])) continue;

                $featureId = $features[$featureName]->id;
                $action = array_search($actionPart, $this->actionMap);

                if ($action !== false) {
                    $region = $permission->region;
                    if (!isset($permissions[$region])) {
                        $permissions[$region] = [];
                    }
                    if (!isset($permissions[$region][$featureId])) {
                        $permissions[$region][$featureId] = [];
                    }
                    if ($action != 'r') {
                        $permissions[$region][$featureId][] = $action;
                    }
                }
            }
        }

        if ($isSuperAdmin) {
            foreach ($features as $feature) {
                foreach ($this->actionMap as $action => $actionFullName) {
                    foreach (['hk', 'mo', 'tw'] as $region) {
                        if (!isset($permissions[$region])) {
                            $permissions[$region] = [];
                        }
                        if (!isset($permissions[$region][$feature->id])) {
                            $permissions[$region][$feature->id] = [];
                        }
                        if ($action !== 'r') {
                            $permissions[$region][$feature->id][] = $action;
                        }
                    }
                }
            }
        }

        foreach ($permissions as $region => &$regionPermissions) {
            foreach ($regionPermissions as $featureId => &$actions) {
                $actions = array_unique($actions);
                sort($actions);
            }
        }

        foreach ($permissions as $region => &$regionPermissions) {
            ksort($regionPermissions);
        }

        $regionPermissionsFormatted = [];
        foreach ($permissions as $region => $permissions) {
            $regionPermissionsFormatted[] = [
                'region'      => $region,
                'permissions' => $permissions
            ];
        }

        return $regionPermissionsFormatted;
    }

    /**
     * Delete a role.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteRole(string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->syncPermissions([]);
        $role->delete();

        return $this->success();
    }
}
