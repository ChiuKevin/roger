<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Get tags
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->tagService->getTags();
    }

    /**
     * Create tag
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'zh_hk'       => 'required|string|max:50',
            'zh_mo'       => 'required|string|max:50',
            'zh_tw'       => 'required|string|max:50',
            'en'          => 'required|string|max:50',
            'is_disabled' => 'required|boolean',
        ]);

        return $this->tagService->createTag($data);
    }

    /**
     * Get tag
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->tagService->getTagById($id);
    }

    /**
     * Update tag
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'zh_hk'       => 'required|string|max:50',
            'zh_mo'       => 'required|string|max:50',
            'zh_tw'       => 'required|string|max:50',
            'en'          => 'required|string|max:50',
            'is_disabled' => 'required|boolean',
        ]);

        return $this->tagService->updateTag($data, $id);
    }

    /**
     * Delete tag
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->tagService->deleteTag($id);
    }
}
