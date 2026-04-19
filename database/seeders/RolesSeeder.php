<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $roles = [
            ['name' => 'admin-super', 'remark' => '系統管理員'],
            ['name' => 'admin-hk', 'remark' => '管理香港區'],
            ['name' => 'admin-mo', 'remark' => '管理澳門區'],
            ['name' => 'admin-tw', 'remark' => '管理台灣區'],
            ['name' => 'user', 'remark' => '一般使用者'],
            ['name' => 'guest', 'remark' => '無操作權限'],
        ];

        $values = [];

        foreach ($roles as $role) {
            $values[] = ['name' => $role['name'], 'guard_name' => 'admin', 'remark' => $role['remark'], 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('roles')->insert($values);
    }
}
