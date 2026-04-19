<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_new_quote',
        'email_quote_updated',
        'email_new_message',
        'email_info',
        'push_new_quote',
        'push_quote_updated',
        'push_new_message',
        'push_system',
        'sms_quote_updated',
        'sms_booking_success',
        'pro_email_credit_refund',
        'pro_email_new_request',
        'pro_email_quote_updated',
        'pro_email_quote_viewed'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
