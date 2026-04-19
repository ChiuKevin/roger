<?php

namespace App\Services\Pro;

use App\Services\Common\BaseUserService;
use Illuminate\Http\JsonResponse;

class UserService extends BaseUserService
{
    public function getNotificationSetting(): JsonResponse
    {
        $user = auth()->user();

        $notification_setting = $user->notificationSetting;
        if ($notification_setting) {
            $notification_setting = $notification_setting->only([
                'pro_email_credit_refund',
                'pro_email_new_request',
                'pro_email_quote_updated',
                'pro_email_quote_viewed'
            ]);
            return $this->success($notification_setting);
        }

        return $this->error(__('error.404', ['attribute' => 'Notification Setting']), 404);
    }
}
