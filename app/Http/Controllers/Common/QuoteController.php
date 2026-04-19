<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    /**
     * Get quotes
     */
    public function index(): JsonResponse
    {
        return $this->quoteService->getQuotes();
    }

    /**
     * Create quote
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'job_category_id' => 'required|int',
            'user_id'         => 'required|int',
            'districts'       => 'required|array',
            'districts.*'     => 'required|int',
            'qna'             => 'required|array',
            'qna.*.question'  => 'required|string',
            'qna.*.answer'    => 'required|array',
            'qna.*.answer.*'  => 'required|string'
        ]);

        return $this->quoteService->createQuote($data);
    }

    /**
     * Get quote
     */
    public function show(string $id): JsonResponse
    {
        return $this->quoteService->getQuote($id);
    }

    /**
     * Update quote
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'status' => 'sometimes|int'
        ]);

        return $this->quoteService->updateQuote($data, $id);
    }

    /**
     * Delete quote
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->quoteService->deleteQuote($id);
    }
}
