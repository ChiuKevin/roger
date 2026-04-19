<?php

namespace App\Services\Consumer;

use App\Models\Banner;
use App\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BannerService extends Service
{
    public function getBannersByRegion(): JsonResponse
    {
        $cache_key = 'consumer:banners:' . app('region');
        $query_conditions = [
            'region'        => app('region'),
            'is_disabled'   => 0,
            'position_type' => 1,
        ];

        $grouped_banners = $this->getCachedBanners($cache_key, $query_conditions, Banner::HOMEPAGE_POSITION_MAPPING);

        return $this->success($grouped_banners);
    }


    public function getBannerByJobCategoryMenus(string $menu_id): array
    {
        $cache_key = 'consumer:job_category_menus:' . $menu_id . ':banners:' . app('region');
        $query_conditions = [
            'region'        => app('region'),
            'is_disabled'   => 0,
            'position_type' => 2,
            'menu_id'       => $menu_id,
        ];

        return $this->getCachedBanners($cache_key, $query_conditions, Banner::JOB_CATEGORIES_POSITION_MAPPING);
    }

    private function getCachedBanners(string $cache_key, array $query_conditions, array $positionMapping): array
    {
        return Cache::remember($cache_key, 86400, function () use ($query_conditions, $positionMapping) {
            $current_date = now()->startOfDay();
            $banners = Banner::where($query_conditions)
                ->where('start_time', '<=', $current_date)
                ->where('end_time', '>=', $current_date)
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'link_type', 'name', 'position_type', 'position_id', 'image', 'link', 'end_time']);

            return $this->processBanners($banners, $positionMapping, $current_date);
        });
    }

    private function processBanners(object $banners, array $positionMapping, object $current_date): array
    {
        $grouped_banners = [];
        foreach ($positionMapping as $position_id => $position_name) {
            $grouped_banners[$position_name] = [];
        }

        $banners_grouped = $banners->groupBy('position_id');

        foreach ($banners_grouped as $position_id => $banners) {
            if (isset($positionMapping[$position_id])) {
                $position_name = $positionMapping[$position_id];
                $banners_with_countdown = $banners->map(function ($banner) use ($current_date) {
                    $end_date = Carbon::parse($banner->end_time)->addDay()->startOfDay();
                    $count_down = $end_date->diffInDays($current_date, true);
                    if ($count_down < 0) {
                        $count_down = 0;
                    }
                    $banner->count_down = $count_down;
                    return $banner;
                })->toArray();

                $grouped_banners[$position_name] = $banners_with_countdown;
            }
        }

        return $grouped_banners;
    }
}
