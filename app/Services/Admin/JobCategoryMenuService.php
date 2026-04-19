<?php

namespace App\Services\Admin;

use App\Models\JobCategoryMenu;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobCategoryMenuService extends Service
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get hierarchical menus by region with translations.
     *
     * @return JsonResponse
     */
    public function getMenusByRegion(): JsonResponse
    {
        $menus = JobCategoryMenu::where('region', app('region'))
            ->where(['parent_id' => 0])
            ->with(['children' => function ($query) {
                $query->select('id', 'parent_id', 'image');
            }])
            ->get(['id', 'parent_id', 'image']);
        $translations_name = $this->getTranslations('job_category_menus', 'name', app('locale'));

        $menus->each(function ($menu) use ($translations_name) {
            $menu->name = $translations_name[$menu->id] ?? null;
            $menu->children->each(function ($children) use ($translations_name) {
                $children->name = $translations_name[$children->id] ?? null;
            });
        });

        return $this->success($menus);
    }

    /**
     * Get job category menu with its relations by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getCategoryMenuWithRelations(string $id): JsonResponse
    {
        $category_menu = JobCategoryMenu::findOrFail($id);

        $translations_name = $this->getTranslations('job_category_menus', 'name');
        $translations_description = $this->getTranslations('job_category_menus', 'description');

        $category_menu_array = $category_menu->toArray();
        $category_menu_array['name'] = $translations_name[$category_menu->id] ?? null;
        $category_menu_array['description'] = $translations_description[$category_menu->id] ?? null;

        return $this->success($category_menu_array);
    }

    /**
     * Update job category menu.
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateCategoryMenu(array $data, string $id): JsonResponse
    {
        $data['region'] = app('region');
        $data['updater'] = $this->authService->getUserInfo()->original['data']['username'];

        DB::beginTransaction();

        try {
            $job_category_menu = JobCategoryMenu::find($id);
            if (!$job_category_menu) {
                return $this->error(__('error.404', ['attribute' => 'Job Category Menu']), 404);
            }

            $translations = [
                'name'        => $data['name'] ?? [],
                'description' => $data['description'] ?? [],
            ];
            unset($data['name'], $data['description']);

            $job_category_menu->update($data);

            $this->setTranslations('job_category_menus', 'name', $id, $translations['name']);
            $this->setTranslations('job_category_menus', 'description', $id, $translations['description']);

            DB::commit();
            $cache_keys = [
                'consumer:job_category_menus:' . app('region') . '_' . app('locale'),
                'consumer:job_category_menus:all:' . app('region') . '_' . app('locale')
            ];
            foreach ($cache_keys as $cache_key) {
                Cache::forget($cache_key);
            }
            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update job category menu', [
                'id'    => $id,
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'job category menu']), 500);
        }
    }
}
