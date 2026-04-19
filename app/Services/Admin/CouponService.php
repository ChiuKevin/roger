<?php

namespace App\Services\Admin;

use App\Models\Coupon;
use App\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponService extends Service
{
    public function getCoupons(): JsonResponse
    {
        $coupons = Coupon::all();

        $translations_name = $this->getTranslations('coupons', 'description', app('locale'));
        $coupons = $coupons->map(function ($coupon) use ($translations_name) {
            $coupon->description = $translations_name[$coupon->id] ?? null;
            return $coupon;
        });

        return $this->successList($coupons);
    }

    public function createCoupon(array $data): JsonResponse
    {
        DB::beginTransaction();
        try {
            $translations = [
                'description' => $data['description'] ?? [],
            ];
            $job_categories = $data['job_categories'] ?? [];
            unset($data['description'], $data['job_categories']);

            $coupon = Coupon::create($data);

            $this->setTranslations('coupons', 'description', $coupon->id, $translations['description']);

            $coupon->jobCategories()->sync($job_categories);

            DB::commit();
            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create coupon', [
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.create', ['attribute' => 'coupon']), 500);
        }
    }

    public function getCouponById(string $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);
        $translations_description = $this->getTranslations('coupons', 'description');

        $coupon_array = $coupon->toArray();
        $coupon_array['description'] = $translations_description[$coupon->id] ?? null;
        $coupon_array['job_categories'] = $coupon->jobCategories->pluck('id')->all();

        return $this->success($coupon_array);
    }

    public function updateCoupon(array $data, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $coupon = Coupon::find($id);
            if (!$coupon) {
                return $this->error(__('error.404', ['attribute' => 'Coupon']), 404);
            }

            $translations = [
                'description' => $data['description'] ?? [],
            ];
            $job_categories = $data['job_categories'] ?? [];
            unset($data['description'], $data['job_categories']);

            $coupon->update($data);

            $this->setTranslations('coupons', 'description', $coupon->id, $translations['description']);

            $coupon->jobCategories()->sync($job_categories);

            DB::commit();
            return $this->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update coupon', [
                'id'    => $id,
                'data'  => $data,
                'error' => $e->getMessage(),
            ]);

            return $this->error(__('error.update', ['attribute' => 'coupon']), 500);
        }
    }

    public function deleteCoupon(string $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);
        $this->removeTranslations('coupons', 'description', $id);
        $coupon->delete();

        return $this->success();
    }
}
