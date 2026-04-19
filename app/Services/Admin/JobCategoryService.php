<?php

namespace App\Services\Admin;

use App\Models\JobCategory;
use App\Models\JobCategoryMenu;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobCategoryService extends Service
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get categories by menu id.
     *
     * @param string $menu_id
     * @return JsonResponse
     */
    public function getCategoriesByMenuId(string $menu_id): JsonResponse
    {
        $menu = JobCategoryMenu::find($menu_id);
        $categories = $menu->categories()->select('job_categories.id', 'job_categories.type', 'job_categories.price', 'job_categories.image', 'job_categories.is_hot', 'job_categories.sort', 'job_categories.is_disabled')->get();

        $translations_name = $this->getTranslations('job_categories', 'name', app('locale'));
        $translations_description = $this->getTranslations('job_categories', 'description', app('locale'));
        $categories->each(function ($category) use ($translations_name, $translations_description) {
            $category->name = $translations_name[$category->id] ?? null;
            $category->description = $translations_description[$category->id] ?? null;
            $category->makeHidden('pivot');
        });

        return $this->successList($categories);
    }

    /**
     * Create a new job category.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createCategory(array $data): JsonResponse
    {
        $data['region'] = app('region');
        $data['creator'] = $this->authService->getUserInfo()->original['data']['username'];

        DB::beginTransaction();
        try {
            $translations = [
                'name'        => $data['name'] ?? [],
                'description' => $data['description'] ?? [],
            ];
            $menu_ids = $data['menu_ids'] ?? [];
            unset($data['name'], $data['description'], $data['menu_ids']);

            $job_category = JobCategory::create($data);

            $this->setCategoryTranslations($job_category->id, $translations);

            $job_category->menus()->sync($menu_ids);

            DB::commit();

            $this->clearCategoryCacheByMenus($menu_ids);

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create job category', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'job category']), 500);
        }
    }

    /**
     * Get job category with its relations by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getCategoryWithRelations(string $id): JsonResponse
    {
        $category = JobCategory::findOrFail($id);

        $translations_name = $this->getTranslations('job_categories', 'name');
        $translations_description = $this->getTranslations('job_categories', 'description');

        $category_array = $category->toArray();
        $category_array['name'] = $translations_name[$category->id] ?? null;
        $category_array['description'] = $translations_description[$category->id] ?? null;
        $category_array['menu_ids'] = $category->menus->pluck('id')->all();

        return $this->success($category_array);
    }

    /**
     * Update job category.
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateCategory(array $data, string $id): JsonResponse
    {
        $data['region'] = app('region');
        $data['updater'] = $this->authService->getUserInfo()->original['data']['username'];

        DB::beginTransaction();

        try {
            $job_category = JobCategory::find($id);
            if (!$job_category) {
                return $this->error(__('error.404', ['attribute' => 'Job Category']), 404);
            }

            $original_menu_ids = $job_category->menus()->pluck('menu_id')->toArray();

            $translations = [
                'name'        => $data['name'] ?? [],
                'description' => $data['description'] ?? [],
            ];
            $menu_ids = $data['menu_ids'] ?? [];
            unset($data['name'], $data['description'], $data['menu_ids']);

            $job_category->update($data);

            $this->setCategoryTranslations($id, $translations);

            $job_category->menus()->sync($menu_ids);

            DB::commit();

            $all_related_menu_ids = array_unique(array_merge($original_menu_ids, $menu_ids));
            $this->clearCategoryCacheByMenus($all_related_menu_ids);

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update job category', [
                'id'    => $id,
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'job category']), 500);
        }
    }

    private function setCategoryTranslations(int $category_id, array $translations): void
    {
        $this->setTranslations('job_categories', 'name', $category_id, $translations['name']);
        $this->setTranslations('job_categories', 'description', $category_id, $translations['description']);
    }

    private function clearCategoryCacheByMenus(array $menu_ids): void
    {
        $parent_menu_ids = JobCategoryMenu::whereIn('id', $menu_ids)
            ->pluck('parent_id')
            ->toArray();

        $parent_menu_ids = array_unique($parent_menu_ids);

        foreach ($parent_menu_ids as $parent_id) {
            $cache_key = 'consumer:job_category_menus:' . $parent_id . ':job_categories:' . app('region') . '_' . app('locale');
            Cache::forget($cache_key);
        }

        $cache_keys = [
            'consumer:job_categories:hot:' . app('region') . '_' . app('locale'),
            'consumer:job_category_menus:all:' . app('region') . '_' . app('locale')
        ];
        foreach ($cache_keys as $cache_key) {
            Cache::forget($cache_key);
        }
    }
}
