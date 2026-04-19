<?php

return [
    //以下是基於 http 狀態碼的通用錯誤
    400        => '錯誤請求。',
    401        => '未授權。',
    403        => '禁止訪問。',
    404        => ':attribute 未找到。',

    //以下是基於 CRUD 的操作錯誤
    'create'   => '建立 :attribute 失敗。',
    'read'     => '讀取 :attribute 失敗。',
    'update'   => '更新 :attribute 失敗。',
    'delete'   => '刪除 :attribute 失敗。',

    //Auth 登入註冊相關
    'auth'     => [
        'user_fully_registered' => '此用戶已經註冊過了。',
        'not_login'             => '未登入。',
    ],

    //Role 角色相關
    'role'     => [
        'permissions_missing' => '缺少以下權限： :attribute',
    ],

    //Sms 短訊相關
    'sms'      => [
        'too_many_retries'  => '重試次數過多，請等待幾個小時再發送驗證碼。',
        'wait_before_retry' => '請等待 :seconds 秒後再發送驗證碼。',
    ],

    //Callback 回調相關
    'callback' => [
        'missing_parameters' => '參數缺失。',
        'invalid_signature'  => '簽名錯誤。',
        'order_not_found'    => '查無此單號： :attribute',
    ],

    //Coupon 折扣券相關
    'coupon'   => [
        'invalid'  => '兌換碼無效。',
        'expired'  => '此兌換碼已過期。',
        'redeemed' => '您已兌換過此優惠。',
    ]
];
