<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Services\Consumer\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display current user's coupons.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->couponService->getUserCoupons();
    }

    /**
     * Redeem Coupons.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function redeem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        return $this->couponService->redeemCoupon($data);
    }
}
