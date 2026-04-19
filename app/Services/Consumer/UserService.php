<?php

namespace App\Services\Consumer;

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
                'email_new_quote',
                'email_quote_updated',
                'email_new_message',
                'push_new_quote',
                'push_quote_updated',
                'push_new_message',
                'push_system',
                'sms_quote_updated',
                'sms_booking_success'
            ]);
            return $this->success($notification_setting);
        }

        return $this->error(__('error.404', ['attribute' => 'Notification Setting']), 404);
    }
}
