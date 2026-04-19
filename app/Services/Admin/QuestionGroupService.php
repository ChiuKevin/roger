<?php

namespace App\Services\Admin;

use App\Models\QuestionGroup;
use App\Models\QuestionGroupQuestion;
use App\Models\Translation;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionGroupService extends Service
{
    /**
     * Get question groups
     *
     * @return JsonResponse
     */
    public function getQuestionGroups(): JsonResponse
    {
        $questionGroups = QuestionGroup::all();

        return $this->successList($questionGroups);
    }

    /**
     * Create question group
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createquestionGroup(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $id = QuestionGroup::create(['name' => $data['name']])->id;

            $values = [];

            foreach ($data['questions'] as $question_id) {
                $values[] = ['question_group_id' => $id, 'question_id' => $question_id];
            }

            QuestionGroupQuestion::insert($values);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create question group', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'question group']), 500);
        }
    }

    /**
     * Get question group
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getQuestionGroup(string $id): JsonResponse
    {
        $questionGroup = QuestionGroup::with(['questions.options'])->find($id);

        if (!$questionGroup) {
            return $this->error(__('error.404', ['attribute' => 'question group']), 404);
        }

        $translations = Translation::whereIn('table', ['questions', 'question_options'])
            ->get()
            ->groupBy('table')
            ->mapWithKeys(function ($items, $key) {
                return [$key => json_decode($items->first()->translation, true)];
            });

        $questions = [];

        foreach ($questionGroup['questions'] as $question) {
            $options = [];

            foreach ($question['options'] as $option) {
                $options[] = [
                    'value' => $option['id'],
                    'label' => $translations['question_options'][$option['id']][app('locale')] ?? ''
                ];
            }

            $questions[] = [
                'id'      => $question['id'],
                'type'    => config('constants.QUESTION_TYPE')[$question['type']] ?? '',
                'name'    => $translations['questions'][$question['id']][app('locale')] ?? '',
                'options' => $options
            ];
        }

        $data = ['list' => $questions];

        return $this->success($data);
    }

    /**
     * Update question group
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updatequestionGroup(array $data, string $id): JsonResponse
    {
        $questionGroup = QuestionGroup::find($id);

        if (!$questionGroup) {
            return $this->error(__('error.404', ['attribute' => 'question group']), 404);
        }

        DB::beginTransaction();

        try {
            $questionGroup->fill($data)->save();

            if (isset($data['questions'])) {
                QuestionGroupQuestion::where('question_group_id', $id)->delete();

                $values = [];

                foreach ($data['questions'] as $question_id) {
                    $values[] = ['question_group_id' => $id, 'question_id' => $question_id];
                }

                QuestionGroupQuestion::insert($values);
            }

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update question group', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'question group']), 500);
        }
    }

    /**
     * Delete question group
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteQuestionGroup(string $id): JsonResponse
    {
        $questionGroup = questionGroup::find($id);

        if (!$questionGroup) {
            return $this->error(__('error.404', ['attribute' => 'question group']), 404);
        }

        QuestionGroup::destroy($id);

        return $this->success();
    }
}
