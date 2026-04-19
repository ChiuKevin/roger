<?php

namespace App\Services\Common;

use App\Models\Quote;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuoteService extends Service
{
    /**
     * Get quotes
     */
    public function getQuotes(): JsonResponse
    {
        $quotes = Quote::all();

        foreach ($quotes as &$quote) {
            $quote['qna'] = json_decode($quote['qna']);
        }

        return $this->successList($quotes);
    }

    /**
     * Create quote
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createQuote(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $quote = [
                'status'          => 1,
                'job_category_id' => $data['job_category_id'],
                'user_id'         => $data['user_id'],
                'credits'         => 15,
                'qna'             => json_encode($data['qna'])
            ];

            $quote = Quote::create($quote);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create quote', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'quote']), 500);
        }
    }

    /**
     * Get quote
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getQuote(string $id): JsonResponse
    {
        $quote = Quote::with('quotePros')->find($id);

        if (!$quote) {
            return $this->error(__('error.404', ['attribute' => 'quote']), 404);
        }

        $quote['qna'] = json_decode($quote['qna']);

        return $this->success($quote);
    }

    /**
     * Update quote
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateQuote(array $data, string $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return $this->error(__('error.404', ['attribute' => 'quote']), 404);
        }

        DB::beginTransaction();

        try {
            $quote->fill($data)->save();

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update quote', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'quote']), 500);
        }
    }

    /**
     * Delete quote
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteQuote(string $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return $this->error(__('error.404', ['attribute' => 'quote']), 404);
        }

        DB::beginTransaction();

        try {
            $quote->delete();

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete quote', [
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.delete', ['attribute' => 'quote']), 500);
        }
    }
}
