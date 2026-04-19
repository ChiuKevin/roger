<?php

namespace App\Services\Consumer;

use App\Models\JobCategory;
use App\Models\JobCategoryMenu;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class JobCategoryService extends Service
{
    protected BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Get job categories by menu id.
     *
     * @param string $menu_id
     * @return JsonResponse
     */
    public function getCategoriesByMenuId(string $menu_id): JsonResponse
    {
        $cache_key = 'consumer:job_category_menus:' . $menu_id . ':job_categories:' . app('region') . '_' . app('locale');

        $job_categories = Cache::remember($cache_key, 86400, function () use ($menu_id) {
            $menu = JobCategoryMenu::find($menu_id);
            $job_categories = $menu->children()->select('id', 'image')->get();

            $sub_menu_names = $this->getTranslations('job_category_menus', 'name', app('locale'));
            $sub_menu_descriptions = $this->getTranslations('job_category_menus', 'description', app('locale'));
            $category_names = $this->getTranslations('job_categories', 'name', app('locale'));
            $category_descriptions = $this->getTranslations('job_categories', 'description', app('locale'));

            $job_categories->each(function ($sub_menu) use ($sub_menu_names, $sub_menu_descriptions, $category_names, $category_descriptions) {
                $sub_menu->name = $sub_menu_names[$sub_menu->id] ?? null;
                $sub_menu->description = $sub_menu_descriptions[$sub_menu->id] ?? null;

                $categories = $sub_menu->categories()
                    ->where('is_disabled', 0)
                    ->orderBy('sort')
                    ->select('job_categories.id', 'job_categories.type', 'job_categories.image', 'job_categories.is_hot', 'job_categories.sort')
                    ->get()->toArray();

                foreach ($categories as &$category) {
                    $category['name'] = $category_names[$category['id']] ?? null;
                    $category['description'] = $category_descriptions[$category['id']] ?? null;
                    unset($category['pivot']);
                }

                $sub_menu->categories = $categories;
            });
            return $job_categories->toArray();
        });

        $banners = $this->bannerService->getBannerByJobCategoryMenus($menu_id);

        $result = [
            'job_categories' => $job_categories,
            'banners'        => $banners,
        ];

        return $this->success($result);
    }

    /**
     * Get hot job categories for homepage.
     *
     * @return JsonResponse
     */
    public function getHotCategoriesByRegion(): JsonResponse
    {
        $cache_key = 'consumer:job_categories:hot:' . app('region') . '_' . app('locale');
        $hot_categories = Cache::remember($cache_key, 86400, function () {
            $hot_categories = JobCategory::where([
                'region'      => app('region'),
                'is_disabled' => 0,
                'is_hot'      => 1
            ])
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'type', 'image']);
            $translations_name = $this->getTranslations('job_categories', 'name', app('locale'));

            $hot_categories->each(function ($hot_category) use ($translations_name) {
                $hot_category->name = $translations_name[$hot_category->id] ?? null;
            });
            return $hot_categories->toArray();
        });

        return $this->success($hot_categories);
    }
}
