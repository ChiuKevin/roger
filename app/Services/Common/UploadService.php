<?php

namespace App\Services\Common;

use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UploadService extends Service
{
    /**
     * Upload a file.
     *
     * @param array $data
     * @param string $type
     * @return JsonResponse
     */
    public function upload(array $data, string $type): JsonResponse
    {
        $directory = isset($data['path']) ? $type . '/' . $data['path'] : $type;

        $path = $data['file']->store($directory);
        $url = Storage::disk()->url($path);

        return $this->success(['url' => $url, 'type' => $type]);
    }

}
