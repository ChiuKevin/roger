<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = [
            ['username' => 'admin', 'email' => 'admin@vornehk.com', 'password' => '12345678'],
            ['username' => 'test', 'email' => 'test@vornehk.com', 'password' => '12345678']
        ];
        $now = now();

        $values = [];

        foreach ($adminUsers as $adminUser) {
            $values[] = [
                'username' => $adminUser['username'],
                'email' => $adminUser['email'],
                'password' => bcrypt($adminUser['password']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('admin_users')->insert($values);
    }
}
