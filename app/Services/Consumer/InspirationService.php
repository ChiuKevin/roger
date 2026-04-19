<?php

namespace App\Services\Consumer;

use App\Services\Service;
use Illuminate\Http\JsonResponse;

class InspirationService extends Service
{
    protected int $unit_price = 50;

    public function estimator(array $data): JsonResponse
    {
        $area_size = floor($data['area_size']);
        $materials = $area_size * $this->unit_price;

        $result = [
            'design_renovation' => [
                'budget'      => $materials * 18,
                'basic_needs' => $materials * 17,
                'materials'   => $materials,
            ],
            'design_only'       => [
                'budget' => $materials * 0.8,
            ],
            'renovation_only'   => [
                'budget'      => $materials * 13,
                'basic_needs' => $materials * 12,
                'materials'   => $materials,
            ]
        ];

        return $this->success($result);
    }
}
