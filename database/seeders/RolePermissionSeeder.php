<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\AdminUser;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUserRoleMap = [
            'admin' => ['admin-super'],
            'admin-hk' => ['admin-hk'],
            'admin-mo' => ['admin-mo'],
            'admin-tw' => ['admin-tw'],
            'user' => ['user'],
            'guest' => ['guest']
        ];

        $regions = ['hk', 'mo', 'tw'];

        $roles = Role::all();

        foreach ($roles as $role) {
            if (strpos($role->name, 'admin') !== false) {
                if ($role->name == 'admin-super') {
                    $allAdminPermissions = Permission::whereIn('region', $regions)->get();
                    $role->syncPermissions($allAdminPermissions);
                } else {
                    foreach ($regions as $region) {
                        if (strpos($role->name, $region) !== false) {
                            $permissions = Permission::where('region', $region)->get();
                            $role->syncPermissions($permissions);
                        }
                    }
                }
            } elseif ($role->name == 'user') {
                $permissions = Permission::whereIn('name', ['create-order', 'read-order', 'update-order'])
                    ->where('region', 'none')
                    ->get();
                $role->syncPermissions($permissions);
            } elseif ($role->name == 'guest') {
                $permissions = Permission::where('name', 'read-order')
                    ->where('region', 'none')
                    ->get();
                $role->syncPermissions($permissions);
            }
        }

        $users = AdminUser::all();
        foreach ($users as $user) {
            if (!isset($adminUserRoleMap[$user->username])) continue;
            $rolesForUser = $adminUserRoleMap[$user->username];
            foreach ($rolesForUser as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
