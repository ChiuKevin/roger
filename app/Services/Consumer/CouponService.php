<?php

namespace App\Services\Consumer;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Services\Service;
use Illuminate\Http\JsonResponse;

class CouponService extends Service
{
    public function getUserCoupons(): JsonResponse
    {
        $user_id = auth()->user()->id;

        $unused_coupons = UserCoupon::with('coupon')
            ->where('user_id', $user_id)
            ->whereNull('redeemed_at')
            ->get()
            ->map(function ($user_coupon) {
                $coupon = collect($user_coupon->coupon)->only(['id', 'coupon_code', 'valid_from', 'valid_until']);
                $translations_name = $this->getTranslations('coupons', 'description', app('locale'));
                $coupon['description'] = $translations_name[$user_coupon->coupon->id] ?? null;
                return $coupon;
            });

        $valid_coupons = $unused_coupons->filter(function ($coupon) {
            return $coupon['valid_until'] >= now()->toDateString();
        })->values();

        $expired_coupons = $unused_coupons->filter(function ($coupon) {
            return $coupon['valid_until'] < now()->toDateString();
        })->values();

        $coupons = [
            'valid_coupons'   => $valid_coupons,
            'expired_coupons' => $expired_coupons,
        ];

        return $this->success($coupons);
    }

    public function redeemCoupon($coupon_code): JsonResponse
    {
        $user_id = auth()->user()->id;

        $coupon = Coupon::where('coupon_code', $coupon_code)->first();

        if (!$coupon || $coupon->valid_from > now()->toDateString()) {
            return $this->error(__('error.coupon.invalid'), 404);
        }

        if ($coupon->valid_until < now()->toDateString()) {
            return $this->error(__('error.coupon.expired'));
        }

        $existing_coupon = UserCoupon::where('user_id', $user_id)
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($existing_coupon) {
            return $this->error(__('error.coupon.redeemed'));
        }

        UserCoupon::create([
            'user_id'    => $user_id,
            'coupon_id'  => $coupon->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->success();
    }
}
