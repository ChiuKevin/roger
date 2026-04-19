<?php

namespace App\Services\Common;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Translation;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionService extends Service
{
    /**
     * Get questions
     *
     * @return JsonResponse
     */
    public function getQuestions(): JsonResponse
    {
        $questions = Question::all();
        $translations = Translation::whereIn('table', ['questions', 'question_options'])
            ->get()
            ->groupBy('table')
            ->mapWithKeys(function ($items, $key) {
                return [$key => json_decode($items->first()->translation, true)];
            });

        foreach ($questions as &$question) {
            $question['category'] = config("constants.QUESTION_CATEGORY.{$question['category']}")[app('locale')];
            $question['type'] = config("constants.QUESTION_TYPE.{$question['type']}");
            $question['name'] = $translations['questions'][$question->id][app('locale')];
        }

        return $this->successList($questions);
    }

    /**
     * Create question
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createQuestion(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data['type'] = array_search($data['type'], config("constants.QUESTION_TYPE"));

            $question = Question::create([
                'category'   => $data['category'],
                'type'       => $data['type'],
                'is_addable' => $data['is_addable'],
                'is_custom'  => $data['is_custom']
            ]);

            $translations = [
                'zh_hk' => $data['title']['zh_hk'],
                'zh_mo' => $data['title']['zh_hk'],
                'zh_tw' => $data['title']['zh_tw'],
                'en'    => $data['title']['en']
            ];

            $this->setTranslations('questions', 'title', $question['id'], $translations);

            if (in_array($data['type'], [3, 4]) && !empty($data['options'])) {
                foreach ($data['options'] as $option) {
                    $createdOption = QuestionOption::create([
                        'question_id' => $question->id,
                    ]);

                    $translations = [
                        'zh_hk' => $option['zh_hk'],
                        'zh_mo' => $option['zh_hk'],
                        'zh_tw' => $option['zh_tw'],
                        'en'    => $option['en']
                    ];

                    $this->setTranslations('question_options', 'name', $createdOption->id, $translations);
                }
            }

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create question', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'question']), 500);
        }
    }

    /**
     * Get question
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getQuestion(string $id): JsonResponse
    {
        $question = Question::with('options')->find($id);

        if (!$question) {
            return $this->error(__('error.404', ['attribute' => 'question']), 404);
        }

        $translations = Translation::whereIn('table', ['questions', 'question_options'])
            ->get()
            ->groupBy('table')
            ->mapWithKeys(function ($items, $key) {
                return [$key => json_decode($items->first()->translation, true)];
            });

        $locales = ['zh_hk', 'zh_mo', 'zh_tw', 'en'];

        $title = [];

        foreach ($locales as $locale) {
            $title[$locale] = $translations['questions'][$id][$locale] ?? '';
        }

        $question['title'] = $title;

        if (in_array($question['type'], [3, 4]) && !empty($question['options'])) {
            foreach ($question['options'] as &$option) {
                foreach ($locales as $locale) {
                    $option[$locale] = $translations['question_options'][$option->id][$locale] ?? '';
                }
                unset($option['id']);
                unset($option['question_id']);
            }
        }

        $question['type'] = config("constants.QUESTION_TYPE.{$question['type']}");

        return $this->success($question);
    }

    /**
     * Update question
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateQuestion(array $data, string $id): JsonResponse
    {
        $question = Question::find($id);

        if (!$question) {
            return $this->error(__('error.404', ['attribute' => 'question']), 404);
        }

        DB::beginTransaction();

        try {
            $data['type'] = array_search($data['type'], config("constants.QUESTION_TYPE"));

            $question->fill($data)->save();

            $translations = [
                'zh_hk' => $data['title']['zh_hk'],
                'zh_mo' => $data['title']['zh_hk'],
                'zh_tw' => $data['title']['zh_tw'],
                'en'    => $data['title']['en']
            ];

            $this->setTranslations('questions', 'title', $question->id, $translations);

            if (in_array($data['type'], [3, 4]) && !empty($data['options'])) {
                $optionIds = QuestionOption::where('question_id', $question->id)->pluck('id')->toArray();

                QuestionOption::where('question_id', $question->id)->delete();

                foreach ($optionIds as $optionId) {
                    $this->removeTranslations('question_options', 'name', $optionId);
                }

                foreach ($data['options'] as $option) {
                    $createdOption = QuestionOption::create([
                        'question_id' => $question->id,
                    ]);

                    $translations = [
                        'zh_hk' => $option['zh_hk'],
                        'zh_mo' => $option['zh_hk'],
                        'zh_tw' => $option['zh_tw'],
                        'en'    => $option['en']
                    ];

                    $this->setTranslations('question_options', 'name', $createdOption->id, $translations);
                }
            }

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update question', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'question']), 500);
        }
    }

    /**
     * Delete question
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteQuestion(string $id): JsonResponse
    {
        $question = Question::find($id);

        if (!$question) {
            return $this->error(__('error.404', ['attribute' => 'question']), 404);
        }

        DB::beginTransaction();

        try {
            Question::destroy($id);

            $this->removeTranslations('questions', 'title', $id);

            $optionIds = QuestionOption::where('question_id', $question->id)->pluck('id')->toArray();

            QuestionOption::where('question_id', $question->id)->delete();

            if (!empty($optionIds)) {
                foreach ($optionIds as $optionId) {
                    $this->removeTranslations('question_options', 'name', $optionId);
                }
            }

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete question', [
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.delete', ['attribute' => 'question']), 500);
        }
    }

    /**
     * Get question categories
     *
     * @return JsonResponse
     */
    public function getQuestionCategories(): JsonResponse
    {
        $categories = config('constants.QUESTION_CATEGORY');

        $result = [];

        foreach ($categories as $id => $name) {
            $result[] = [
                'id'   => $id,
                'name' => $name[app('locale')]
            ];
        }

        return $this->success($result);
    }
}
