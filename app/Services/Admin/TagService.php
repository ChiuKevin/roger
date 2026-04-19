<?php

namespace App\Services\Admin;

use App\Models\Tag;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagService extends Service
{
    /**
     * Get tags
     *
     * @return JsonResponse
     */
    public function getTags(): JsonResponse
    {
        $translation = DB::table('translations')
            ->where('table', 'tags')
            ->where('column', 'name')
            ->first();

        $translation = json_decode($translation->translation, true);

        $tags = Tag::all();

        foreach ($tags as &$tag) {
            $tag['name'] = $translation[$tag['id']][app('locale')] ?? '';
            $tag['zh_hk'] = $translation[$tag['id']]['zh_hk'] ?? '';
            $tag['zh_mo'] = $translation[$tag['id']]['zh_mo'] ?? '';
            $tag['zh_tw'] = $translation[$tag['id']]['zh_tw'] ?? '';
            $tag['en'] = $translation[$tag['id']]['en'] ?? '';
        }

        return $this->successList($tags);
    }

    /**
     * Create tag
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createTag(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $tagData = [
                'region'      => app('region'),
                'is_disabled' => $data['is_disabled']
            ];

            $tag = Tag::create($tagData);

            $translations = [
                'zh_hk' => $data['zh_hk'],
                'zh_mo' => $data['zh_mo'],
                'zh_tw' => $data['zh_tw'],
                'en'    => $data['en']
            ];

            $this->setTranslations('tags', 'name', $tag->id, $translations);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create tag', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'tag']), 500);
        }
    }

    /**
     * Get tag
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getTagById(string $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return $this->error(__('error.404', ['attribute' => 'Tag']), 404);
        }

        $translation = DB::table('translations')
            ->where('table', 'tags')
            ->where('column', 'name')
            ->value('translation');

        $translation = json_decode($translation, true);

        $translations = $translation[$id] ?? [];
        $locales = ['zh_hk', 'zh_mo', 'zh_tw', 'en'];

        foreach ($locales as $localeKey) {
            $tag[$localeKey] = $translations[$localeKey] ?? '';
        }

        $tag['name'] = $translations[app('locale')] ?? '';

        return $this->success($tag);
    }

    /**
     * Update tag
     *
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function updateTag(array $data, string $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return $this->error(__('error.404', ['attribute' => 'Tag']), 404);
        }

        DB::beginTransaction();

        try {
            $tagData = [
                'region'      => app('region'),
                'is_disabled' => $data['is_disabled']
            ];

            $tag->update($tagData);

            $translations = [
                'zh_hk' => $data['zh_hk'],
                'zh_mo' => $data['zh_mo'],
                'zh_tw' => $data['zh_tw'],
                'en'    => $data['en']
            ];

            $this->setTranslations('tags', 'name', $id, $translations);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to update tag', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'tag']), 500);
        }
    }

    /**
     * Delete tag
     *
     * @param string $id
     * @return JsonResponse
     */
    public function deleteTag(string $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return $this->error(__('error.404', ['attribute' => 'Tag']), 404);
        }

        DB::beginTransaction();

        try {
            Tag::destroy($id);

            $this->removeTranslations('tags', 'name', $id);

            DB::commit();

            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete tag', [
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.delete', ['attribute' => 'tag']), 500);
        }
    }
}
