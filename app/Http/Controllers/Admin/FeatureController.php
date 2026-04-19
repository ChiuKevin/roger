<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\JsonResponse;

class FeatureController extends Controller
{
    /**
     * Get features
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $features = Feature::whereNull('parent_id')->with('children')->get();

        $result = $features->map(function ($feature) {
            return $this->formatFeature($feature);
        });

        return $this->success(['list' => $result]);
    }

    /**
     * Format feature
     * 
     * @param \App\Models\Feature $feature
     * @param string|null $parentName
     * @return array
     */
    private function formatFeature($feature, $parentName = null): array
    {
        $formatted = [
            'id'   => $feature->id,
            'name' => $parentName ? $parentName . '_' . $feature->name : $feature->name,
        ];

        if ($feature->children->isNotEmpty()) {
            $formatted['children'] = $feature->children->map(function ($child) use ($formatted) {
                return $this->formatFeature($child, $formatted['name']);
            })->toArray();
        }

        return $formatted;
    }
}
