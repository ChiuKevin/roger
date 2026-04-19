<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of banners.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->bannerService->getBannersByRegion();
    }

    /**
     * Create a new banner.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'link_type'     => 'required|integer',
            'name'          => 'required|string',
            'position_type' => 'present|nullable|integer',
            'position_id'   => 'required|integer',
            'menu_id'       => 'required_if:position_type,2|integer',
            'sort'          => 'required|integer|min:0',
            'image'         => 'present|nullable|string',
            'link'          => 'present|nullable|string',
            'is_disabled'   => 'required|boolean',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after_or_equal:start_time',
        ]);

        return $this->bannerService->createBanner($data);
    }

    /**
     * Display the specified banner.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->bannerService->getBannerById($id);
    }

    /**
     * Update the specified banner.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'link_type'     => 'required|integer',
            'name'          => 'required|string',
            'position_type' => 'present|nullable|integer',
            'position_id'   => 'required|integer',
            'menu_id'       => 'required_if:position_type,2|integer',
            'sort'          => 'required|integer|min:0',
            'image'         => 'present|nullable|string',
            'link'          => 'present|nullable|string',
            'is_disabled'   => 'required|boolean',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after_or_equal:start_time',
        ]);

        return $this->bannerService->updateBanner($data, $id);
    }

    /**
     * Remove the specified banner.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->bannerService->deleteBanner($id);
    }
}
