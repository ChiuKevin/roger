<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'valid_from',
        'valid_until',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_coupons')->withPivot('redeemed_at');
    }

    public function jobCategories()
    {
        return $this->belongsToMany(JobCategory::class, 'coupon_job_categories');
    }
}
