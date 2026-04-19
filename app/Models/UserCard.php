<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'region',
        'provider',
        'card_last_four',
        'brand',
        'exp_month',
        'exp_year',
        'card_token',
        'is_default',
    ];

    const PROVIDER_ECPAY = 1;
    const PROVIDER_BBMSL = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
