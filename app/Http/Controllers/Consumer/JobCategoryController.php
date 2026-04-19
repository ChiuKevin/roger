<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Services\Consumer\JobCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    protected JobCategoryService $jobCategoryService;

    public function __construct(JobCategoryService $jobCategoryService)
    {
        $this->jobCategoryService = $jobCategoryService;
    }

    /**
     * Get job categories by menu id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoriesByMenuId(Request $request): JsonResponse
    {
        $data = $request->validate([
            'menu_id' => 'required|string'
        ]);

        return $this->jobCategoryService->getCategoriesByMenuId($data['menu_id']);
    }

    /**
     * Get hot job categories.
     *
     * @return JsonResponse
     */
    public function getHotCategories(): JsonResponse
    {
        return $this->jobCategoryService->getHotCategoriesByRegion();
    }
}
