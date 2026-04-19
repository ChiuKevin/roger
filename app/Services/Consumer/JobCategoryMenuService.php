<?php

namespace App\Services\Consumer;

use App\Models\JobCategoryMenu;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class JobCategoryMenuService extends Service
{

    /**
     * Get hierarchical menus by region with translations.
     *
     * @return JsonResponse
     */
    public function getMenusByRegion(): JsonResponse
    {
        $cache_key = 'consumer:job_category_menus:' . app('region') . '_' . app('locale');

        $menus = Cache::remember($cache_key, 86400, function () {
            $menus = JobCategoryMenu::where('region', app('region'))
                ->where(['parent_id' => 0])
                ->orderBy('id', 'asc')
                ->get(['id']);
            $translations_name = $this->getTranslations('job_category_menus', 'name', app('locale'));

            $menus->each(function ($menu) use ($translations_name) {
                $menu->name = $translations_name[$menu->id] ?? null;
            });
            return $menus->toArray();
        });

        return $this->success($menus);
    }

    public function getAllMenus(): JsonResponse
    {
        $cache_key = 'consumer:job_category_menus:all:' . app('region') . '_' . app('locale');

        $menus = Cache::remember($cache_key, 86400, function () {
            $menus = JobCategoryMenu::where(['region' => app('region'), 'parent_id' => 0])->orderBy('id', 'asc')->get(['id']);

            $job_category_menu_names = $this->getTranslations('job_category_menus', 'name', app('locale'));
            $job_category_names = $this->getTranslations('job_categories', 'name', app('locale'));

            $menus->each(function ($menu) use ($job_category_menu_names, $job_category_names) {
                $menu->name = $job_category_menu_names[$menu->id] ?? null;
                $sub_menus = $menu->children()->get(['id']);
                $sub_menus->each(function ($sub_menu) use ($job_category_menu_names, $job_category_names) {
                    $sub_menu->name = $job_category_menu_names[$sub_menu->id] ?? null;
                    $categories = $sub_menu->categories()->where(['is_disabled' => 0])->orderBy('sort', 'asc')->get(['job_categories.id'])->makeHidden('pivot');
                    $categories->each(function ($category) use ($job_category_names) {
                        $category->name = $job_category_names[$category->id] ?? null;
                    });
                    $sub_menu->categoreis = $categories->toArray();
                });
                $menu->children = $sub_menus->toArray();
            });
            return $menus->toArray();
        });

        return $this->success($menus);
    }
}
