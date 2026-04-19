<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $features = [
            'order' => ['order-list'],
            'user' => ['user-list'],
            'sms' => ['sms-log-list'],
            'settings' => ['admin-user', 'role', 'job-category', 'tag', 'banner']
        ];

        $values = [];
        $id = 1;

        foreach ($features as $parent => $children) {
            $values[] = ['id' => $id, 'parent_id' => null, 'name' => $parent, 'created_at' => $now, 'updated_at' => $now];
            $parent_id = $id++;
            foreach ($children as $child) {
                $values[] = ['id' => $id++, 'parent_id' => $parent_id, 'name' => $child, 'created_at' => $now, 'updated_at' => $now];
            }
        }

        DB::table('features')->insert($values);
    }
}
