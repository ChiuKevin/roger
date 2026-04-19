<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Services\Consumer\JobCategoryMenuService;
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
     * Get the list of all job category menus & job categories.
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->jobCategoryMenuService->getAllMenus();
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
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified job category menu.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Delete a specific job category menu.
     */
    public function destroy(string $id)
    {
        //
    }

}
