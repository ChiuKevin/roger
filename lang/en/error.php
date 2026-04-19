<?php

return [
    //以下是基於 http 狀態碼的通用錯誤
    400        => 'Bad request.',
    401        => 'Unauthorized.',
    403        => 'Forbidden.',
    404        => ':attribute not found.',

    //以下是基於 CRUD 的操作錯誤
    'create'   => 'Failed to create :attribute.',
    'read'     => 'Failed to read :attribute.',
    'update'   => 'Failed to update :attribute.',
    'delete'   => 'Failed to delete :attribute.',

    //Auth 登入註冊相關
    'auth'     => [
        'user_fully_registered' => 'User already fully registered.',
        'not_login'             => 'Not logged in.',
    ],

    //Role 角色相關
    'role'     => [
        'permissions_missing' => 'The following permissions are missing: :attribute',
    ],

    //Sms 短訊相關
    'sms'      => [
        'too_many_retries'  => 'Too many retries, please wait a few hours before sending the verification code.',
        'wait_before_retry' => 'Please wait :seconds seconds before sending the verification code.',
    ],

    //Callback 回調相關
    'callback' => [
        'missing_parameters' => 'Missing parameters.',
        'invalid_signature'  => 'Invalid signature.',
        'order_not_found'    => 'Order not found: :attribute',
    ],

    //Coupon 折扣券相關
    'coupon'   => [
        'invalid'  => 'Redeem code is invalid.',
        'expired'  => 'Redeem code has expired.',
        'redeemed' => 'You have already redeemed this coupon.',
    ]
];
