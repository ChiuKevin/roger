<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\JobCategoryMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCategoryMenuController extends Controller
{
    protected JobCategoryMenuService $jobCategoryMenuService;

    public function __construct(JobCategoryMenuService $jobCategoryMenuService)
    {
        $this->jobCategoryMenuService = $jobCategoryMenuService;
    }

    /**
     * Get the list of job category menus.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->jobCategoryMenuService->getMenusByRegion();
    }

    /**
     * Create a new job category menu.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display details of a specified job category menu.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->jobCategoryMenuService->getCategoryMenuWithRelations($id);
    }

    /**
     * Update the specified job category menu.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'image'       => 'present|nullable|string',
            'name'        => 'required|array',
            'description' => 'required|array',
        ]);

        return $this->jobCategoryMenuService->updateCategoryMenu($data, $id);
    }

    /**
     * Delete a specific job category menu.
     */
    public function destroy(string $id)
    {
        //
    }

}
