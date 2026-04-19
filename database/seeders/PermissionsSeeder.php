<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = ['hk', 'mo', 'tw'];
        $features = $this->getFeatures();
        $now = date('Y-m-d H:i:s');
        $values = [];

        foreach ($regions as $region) {
            foreach ($features as $feature) {
                $values[] = ['region' => $region, 'name' => "create-{$feature}", 'guard_name' => 'admin', 'created_at' => $now, 'updated_at' => $now];
                $values[] = ['region' => $region, 'name' => "read-{$feature}", 'guard_name' => 'admin', 'created_at' => $now, 'updated_at' => $now];
                $values[] = ['region' => $region, 'name' => "update-{$feature}", 'guard_name' => 'admin', 'created_at' => $now, 'updated_at' => $now];
                $values[] = ['region' => $region, 'name' => "delete-{$feature}", 'guard_name' => 'admin', 'created_at' => $now, 'updated_at' => $now];
            }
        }

        DB::table('permissions')->insert($values);
    }

    private function getFeatures(): array
    {
        $routes = Route::getRoutes();

        $features = collect($routes)
            ->filter(function ($route) {
                return in_array('permission', $route->gatherMiddleware());
            })
            ->map(function ($route) {
                $uriParts = explode('/', $route->uri());
                if (end($uriParts) === 'filter' && count($uriParts) > 1) {
                    array_pop($uriParts);
                }
                return end($uriParts);
            })
            ->filter(function ($name) {
                return strpos($name, '{') === false && strpos($name, '}') === false;
            })
            ->unique()
            ->map(function ($name) {
                return Str::singular($name);
            })
            ->values()
            ->all();

        return $features;
    }
}
