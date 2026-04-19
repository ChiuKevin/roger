<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TranslateTrait
{
    /**
     * Get translations for a specific table and column.
     *
     * @param string $table
     * @param string $column
     * @param string $locale
     * @return array
     */
    protected function getTranslations(string $table, string $column, string $locale = ''): array
    {
        $translationRecords = DB::table('translations')
            ->where('table', $table)
            ->where('column', $column)
            ->pluck('translation', 'id')
            ->toArray();

        foreach ($translationRecords as $id => $translation) {
            $translationData = json_decode($translation, true);
            if (is_array($translationData)) {
                $filteredTranslation = [];
                foreach ($translationData as $key => $value) {
                    if (array_key_exists($key, $translationData) && !empty($locale)) {
                        $filteredTranslation[$key] = $value[$locale];
                    } else
                        $filteredTranslation[$key] = $value;
                }
                $translationRecords = $filteredTranslation;
            } else {
                unset($translationRecords[$id]);
            }
        }

        return $translationRecords;
    }

    /**
     * Update or insert translations.
     *
     * @param string $table
     * @param string $column
     * @param int $id
     * @param array $translations
     * @return void
     */
    protected function setTranslations(string $table, string $column, int $id, array $translations): void
    {
        $existingTranslation = DB::table('translations')
            ->where('table', $table)
            ->where('column', $column)
            ->first();

        if ($existingTranslation) {
            $translationData = json_decode($existingTranslation->translation, true);

            if (!isset($translationData[$id]) || $translationData[$id] !== $translations) {
                $translationData[$id] = $translations;

                DB::table('translations')
                    ->where('table', $table)
                    ->where('column', $column)
                    ->update(['translation' => json_encode($translationData, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }

    /**
     * Remove translations for a given table, column, and ID.
     *
     * @param string $table
     * @param string $column
     * @param int $id
     *
     * @return void
     */
    protected function removeTranslations(string $table, string $column, int $id): void
    {
        $existingTranslation = DB::table('translations')
            ->where('table', $table)
            ->where('column', $column)
            ->first();

        if ($existingTranslation) {
            $translationData = json_decode($existingTranslation->translation, true);

            if (isset($translationData[$id])) {
                unset($translationData[$id]);
                DB::table('translations')
                    ->where('table', $table)
                    ->where('column', $column)
                    ->update(['translation' => json_encode($translationData, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }
}
