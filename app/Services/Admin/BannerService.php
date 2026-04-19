<?php

namespace App\Services\Admin;

use App\Models\Banner;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BannerService extends Service
{
    public function getBannersByRegion(): JsonResponse
    {
        $banners_raw = Banner::where('region', app('region'))->get();

        $grouped_banners = [];

        foreach (Banner::POSITION_TYPE_MAPPING as $position_type => $position_type_name) {
            $position_mapping = ($position_type == 1)
                ? Banner::HOMEPAGE_POSITION_MAPPING
                : Banner::JOB_CATEGORIES_POSITION_MAPPING;

            $grouped_banners[] = [
                'position_type'      => $position_type,
                'position_type_name' => $position_type_name,
                'children'           => []
            ];

            foreach ($position_mapping as $position_id => $position_name) {
                $grouped_banners[array_key_last($grouped_banners)]['children'][] = [
                    'position_id'   => $position_id,
                    'position_name' => $position_name,
                    'banners'       => []
                ];
            }
        }

        foreach ($banners_raw as $banner) {
            $position_type = $banner->position_type;
            $position_id = $banner->position_id;

            foreach ($grouped_banners as &$group) {
                if ($group['position_type'] == $position_type) {
                    foreach ($group['children'] as &$child) {
                        if ($child['position_id'] == $position_id) {
                            $child['banners'][] = $banner;
                            break;
                        }
                    }
                    break;
                }
            }
        }

        return $this->successList($grouped_banners, $banners_raw);
    }

    public function createBanner(array $data): JsonResponse
    {
        $data['region'] = app('region');

        try {
            Banner::create($data);

            $cache_key = 'consumer:banners:' . app('region');
            Cache::forget($cache_key);

            if (!empty($data['menu_id'])) {
                $menu_id_cache_key = 'consumer:job_category_menus:' . $data['menu_id'] . ':banners:' . app('region');
                Cache::forget($menu_id_cache_key);
            }

            return $this->success();
        } catch (Exception $e) {
            Log::error('Failed to create banner', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error($e->getMessage());
        }
    }

    public function getBannerById(string $id): JsonResponse
    {
        return $this->success(Banner::findOrFail($id));
    }

    public function updateBanner(array $data, string $id): JsonResponse
    {
        $data['region'] = app('region');

        try {
            $banner = Banner::findOrFail($id);

            $old_menu_id = $banner->menu_id;

            $banner->update($data);

            $cache_key = 'consumer:banners:' . app('region');
            Cache::forget($cache_key);

            if (!empty($old_menu_id)) {
                $old_menu_id_cache_key = 'consumer:job_category_menus:' . $old_menu_id . ':banners:' . app('region');
                Cache::forget($old_menu_id_cache_key);
            }

            if (!empty($data['menu_id'])) {
                $new_menu_id_cache_key = 'consumer:job_category_menus:' . $data['menu_id'] . ':banners:' . app('region');
                Cache::forget($new_menu_id_cache_key);
            }

            return $this->success();
        } catch (Exception $e) {
            Log::error('Failed to update banner', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error($e->getMessage());
        }
    }

    public function deleteBanner(string $id): JsonResponse
    {
        $banner = Banner::findOrFail($id);
        $menu_id = $banner->menu_id;

        $banner->delete();

        $cache_key = 'consumer:banners:' . app('region');
        Cache::forget($cache_key);

        if (!empty($menu_id)) {
            $menu_id_cache_key = 'consumer:job_category_menus:' . $menu_id . ':banners:' . app('region');
            Cache::forget($menu_id_cache_key);
        }

        return $this->success();
    }
}
