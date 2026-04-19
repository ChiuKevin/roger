<?php

namespace App\Services\Web;

use App\Services\Common\BaseUserService;
use Illuminate\Http\JsonResponse;

class UserService extends BaseUserService
{
    public function getNotificationSetting(): JsonResponse
    {
        $user = auth()->user();

        $notification_setting = $user->notificationSetting;

        if ($notification_setting) {
            $fields = [
                'email_new_quote',
                'email_quote_updated',
                'email_new_message',
                'email_info'
            ];

            if ($user->is_pro) {
                $fields = array_merge($fields, [
                    'pro_email_credit_refund',
                    'pro_email_new_request',
                    'pro_email_quote_updated',
                    'pro_email_quote_viewed'
                ]);
            }

            $notification_setting = $notification_setting->only($fields);

            return $this->success($notification_setting);
        }

        return $this->error(__('error.404', ['attribute' => 'Notification Setting']), 404);
    }
}
