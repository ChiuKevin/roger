<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display a listing of the coupons.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->couponService->getCoupons();
    }

    /**
     * Store a new coupon.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'coupon_code'         => 'required|string|unique:coupons,coupon_code',
            'discount_type'       => 'required|in:fixed,percentage',
            'discount_value'      => 'required|numeric',
            'min_purchase_amount' => 'present|nullable|numeric',
            'valid_from'          => 'required|date',
            'valid_until'         => 'required|date',
            'description'         => 'required|array',
            'job_categories'      => 'present|nullable|array'
        ]);

        return $this->couponService->createCoupon($data);
    }

    /**
     * Display the specified coupon.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->couponService->getCouponById($id);
    }

    /**
     * Update the specified coupon.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'coupon_code'         => 'required|string|unique:coupons,coupon_code,' . $id,
            'discount_type'       => 'required|in:fixed,percentage',
            'discount_value'      => 'required|numeric',
            'min_purchase_amount' => 'present|nullable|numeric',
            'valid_from'          => 'required|date',
            'valid_until'         => 'required|date',
            'description'         => 'required|array',
            'job_categories'      => 'present|nullable|array'
        ]);

        return $this->couponService->updateCoupon($data, $id);
    }

    /**
     * Remove the specified coupon.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->couponService->deleteCoupon($id);
    }
}
