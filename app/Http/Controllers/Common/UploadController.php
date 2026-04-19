<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Upload an Image file.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file|image|max:10240',
            'path' => 'sometimes|nullable|string'
        ]);

        return $this->uploadService->upload($data, 'images');
    }

    /**
     * Upload a video file.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadVideo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file|mimes:mp4,avi,mov,mpeg,mkv,flv,webm,wmv|max:20480',
            'path' => 'sometimes|nullable|string'
        ]);

        return $this->uploadService->upload($data, 'videos');
    }

}
