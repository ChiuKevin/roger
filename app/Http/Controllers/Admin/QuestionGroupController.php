<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\questionGroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionGroupController extends Controller
{
    protected QuestionGroupService $questionGroupService;

    public function __construct(QuestionGroupService $questionGroupService)
    {
        $this->questionGroupService = $questionGroupService;
    }

    /**
     * Get question groups
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->questionGroupService->getQuestionGroups();
    }

    /**
     * Create question group
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'questions'   => 'required|array',
            'questions.*' => 'integer',
        ]);

        return $this->questionGroupService->createQuestionGroup($data);
    }

    /**
     * Get question group
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->questionGroupService->getQuestionGroup($id);
    }

    /**
     * Update question group
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'sometimes|string',
            'questions'   => 'present|nullable|array',
            'questions.*' => 'integer',
            'is_disabled' => 'sometimes|boolean'
        ]);

        return $this->questionGroupService->updateQuestionGroup($data, $id);
    }

    /**
     * Delete question group
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->questionGroupService->deleteQuestionGroup($id);
    }
}
