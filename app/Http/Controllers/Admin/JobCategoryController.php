<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\JobCategoryService;
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
     * Get the list of job categories.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'menu_id' => 'required|string'
        ]);

        return $this->jobCategoryService->getCategoriesByMenuId($data['menu_id']);
    }

    /**
     * Create a new job category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'        => 'required|integer',
            'price'       => 'required|integer',
            'image'       => 'present|nullable|string',
            'is_hot'      => 'required|boolean',
            'sort'        => 'present|nullable|integer',
            'is_disabled' => 'required|boolean',
            'name'        => 'required|array',
            'description' => 'required|array',
            'menu_ids'    => 'present|nullable|array',
        ]);

        return $this->jobCategoryService->createCategory($data);
    }

    /**
     * Display details of a specified job category.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->jobCategoryService->getCategoryWithRelations($id);
    }

    /**
     * Update the specified job category with its relations and translations.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'type'        => 'required|integer',
            'price'       => 'required|integer',
            'image'       => 'present|nullable|string',
            'is_hot'      => 'required|boolean',
            'sort'        => 'present|nullable|integer',
            'is_disabled' => 'required|boolean',
            'name'        => 'required|array',
            'description' => 'required|array',
            'menu_ids'    => 'present|nullable|array',
        ]);

        return $this->jobCategoryService->updateCategory($data, $id);
    }

    /**
     * Delete a specific job category.
     */
    public function destroy(string $id)
    {
        //
    }

}
