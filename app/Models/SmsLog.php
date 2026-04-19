<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'country_code',
        'phone',
        'sms_code',
        'batch_id',
        'send_response',
        'callback_response',
        'status'
    ];

    const PROVIDER_EVERY8D = 1;
    const PROVIDER_ABOSEND = 2;

    const STATUS_SENT     = 1;
    const STATUS_NOT_SENT = 2;
    const STATUS_SUCCESS  = 3;
    const STATUS_FAILED   = 4;
}
