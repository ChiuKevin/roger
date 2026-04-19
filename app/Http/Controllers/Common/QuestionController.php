<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Get questions
     */
    public function index(): JsonResponse
    {
        return $this->questionService->getQuestions();
    }

    /**
     * Create question
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category'        => 'required|int',
            'type'            => 'required|string|in:text,textarea,radio,checkbox',
            'is_addable'      => 'required|boolean',
            'is_custom'       => 'required|boolean',
            'title.zh_hk'     => 'required|string|max:100',
            'title.zh_tw'     => 'required|string|max:100',
            'title.en'        => 'required|string|max:100',
            'options'         => 'required_if:type,radio,checkbox|array',
            'options.*.zh_hk' => 'required_if:type,radio,checkbox|string|max:100',
            'options.*.zh_tw' => 'required_if:type,radio,checkbox|string|max:100',
            'options.*.en'    => 'required_if:type,radio,checkbox|string|max:100',
        ]);

        return $this->questionService->createQuestion($data);
    }

    /**
     * Get question
     */
    public function show(string $id): JsonResponse
    {
        return $this->questionService->getQuestion($id);
    }

    /**
     * Update question
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'category'        => 'required|int',
            'type'            => 'required|string|in:text,textarea,radio,checkbox',
            'is_addable'      => 'required|boolean',
            'is_custom'       => 'required|boolean',
            'title.zh_hk'     => 'required|string|max:100',
            'title.zh_tw'     => 'required|string|max:100',
            'title.en'        => 'required|string|max:100',
            'options'         => 'required_if:type,radio,checkbox|array',
            'options.*.zh_hk' => 'required_if:type,radio,checkbox|string|max:100',
            'options.*.zh_tw' => 'required_if:type,radio,checkbox|string|max:100',
            'options.*.en'    => 'required_if:type,radio,checkbox|string|max:100',
        ]);

        return $this->questionService->updateQuestion($data, $id);
    }

    /**
     * Delete question
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->questionService->deleteQuestion($id);
    }

    /**
     * Get question categories
     */
    public function getQuestionCategories(): JsonResponse
    {
        return $this->questionService->getQuestionCategories();
    }
}
