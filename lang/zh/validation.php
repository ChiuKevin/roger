<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => '必須接受 :attribute。',
    'accepted_if'          => '當 :other 是 :value 時，必須接受 :attribute。',
    'active_url'           => ':attribute 必須是有效的 URL。',
    'after'                => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal'       => ':attribute 必須是 :date 之後或相同的日期。',
    'alpha'                => ':attribute 只能包含字母。',
    'alpha_dash'           => ':attribute 只能包含字母、數字、破折號和底線。',
    'alpha_num'            => ':attribute 只能包含字母和數字。',
    'array'                => ':attribute 必須是數組。',
    'ascii'                => ':attribute 只能包含單字節字母數字字符和符號。',
    'before'               => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal'      => ':attribute 必須是 :date 之前或相同的日期。',
    'between'              => [
        'array'   => ':attribute 必須有 :min 到 :max 項。',
        'file'    => ':attribute 必須在 :min 到 :max KB 之間。',
        'numeric' => ':attribute 必須在 :min 到 :max 之間。',
        'string'  => ':attribute 必須在 :min 到 :max 個字符之間。',
    ],
    'boolean'              => ':attribute 字段必須為 true 或 false。',
    'can'                  => ':attribute 字段包含未授權的值。',
    'confirmed'            => ':attribute 確認不匹配。',
    'contains'             => ':attribute 缺少所需的值。',
    'current_password'     => '密碼不正確。',
    'date'                 => ':attribute 必須是有效的日期。',
    'date_equals'          => ':attribute 必須是等於 :date 的日期。',
    'date_format'          => ':attribute 必須符合格式 :format。',
    'decimal'              => ':attribute 必須有 :decimal 位小數。',
    'declined'             => ':attribute 必須被拒絕。',
    'declined_if'          => '當 :other 是 :value 時，必須拒絕 :attribute。',
    'different'            => ':attribute 和 :other 必須不同。',
    'digits'               => ':attribute 必須是 :digits 位數字。',
    'digits_between'       => ':attribute 必須在 :min 到 :max 位數字之間。',
    'dimensions'           => ':attribute 圖片尺寸無效。',
    'distinct'             => ':attribute 字段具有重複值。',
    'doesnt_end_with'      => ':attribute 不能以以下之一結尾: :values。',
    'doesnt_start_with'    => ':attribute 不能以以下之一開頭: :values。',
    'email'                => ':attribute 必須是有效的電子郵件地址。',
    'ends_with'            => ':attribute 必須以以下之一結尾: :values。',
    'enum'                 => '選擇的 :attribute 無效。',
    'exists'               => '選擇的 :attribute 無效。',
    'extensions'           => ':attribute 必須具有以下擴展名之一: :values。',
    'file'                 => ':attribute 必須是文件。',
    'filled'               => ':attribute 必須有一個值。',
    'gt'                   => [
        'array'   => ':attribute 必須多於 :value 項。',
        'file'    => ':attribute 必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string'  => ':attribute 必須多於 :value 個字符。',
    ],
    'gte'                  => [
        'array'   => ':attribute 必須有 :value 項或更多。',
        'file'    => ':attribute 必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string'  => ':attribute 必須大於或等於 :value 個字符。',
    ],
    'hex_color'            => ':attribute 必須是有效的十六進制顏色。',
    'image'                => ':attribute 必須是圖片。',
    'in'                   => '選擇的 :attribute 無效。',
    'in_array'             => ':attribute 字段在 :other 中不存在。',
    'integer'              => ':attribute 必須是整數。',
    'ip'                   => ':attribute 必須是有效的 IP 地址。',
    'ipv4'                 => ':attribute 必須是有效的 IPv4 地址。',
    'ipv6'                 => ':attribute 必須是有效的 IPv6 地址。',
    'json'                 => ':attribute 必須是有效的 JSON 字符串。',
    'list'                 => ':attribute 必須是列表。',
    'lowercase'            => ':attribute 必須是小寫字母。',
    'lt'                   => [
        'array'   => ':attribute 必須少於 :value 項。',
        'file'    => ':attribute 必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string'  => ':attribute 必須少於 :value 個字符。',
    ],
    'lte'                  => [
        'array'   => ':attribute 不能多於 :value 項。',
        'file'    => ':attribute 必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string'  => ':attribute 必須小於或等於 :value 個字符。',
    ],
    'mac_address'          => ':attribute 必須是有效的 MAC 地址。',
    'max'                  => [
        'array'   => ':attribute 不能多於 :max 項。',
        'file'    => ':attribute 不能大於 :max KB。',
        'numeric' => ':attribute 不能大於 :max。',
        'string'  => ':attribute 不能多於 :max 個字符。',
    ],
    'max_digits'           => ':attribute 不能多於 :max 位數字。',
    'mimes'                => ':attribute 必須是以下類型的文件: :values。',
    'mimetypes'            => ':attribute 必須是以下類型的文件: :values。',
    'min'                  => [
        'array'   => ':attribute 必須至少有 :min 項。',
        'file'    => ':attribute 必須至少為 :min KB。',
        'numeric' => ':attribute 必須至少為 :min。',
        'string'  => ':attribute 必須至少為 :min 個字符。',
    ],
    'min_digits'           => ':attribute 必須至少有 :min 位數字。',
    'missing'              => ':attribute 必須缺少。',
    'missing_if'           => '當 :other 是 :value 時，:attribute 必須缺少。',
    'missing_unless'       => '除非 :other 是 :value，否則 :attribute 必須缺少。',
    'missing_with'         => '當 :values 存在時，:attribute 必須缺少。',
    'missing_with_all'     => '當 :values 都存在時，:attribute 必須缺少。',
    'multiple_of'          => ':attribute 必須是 :value 的倍數。',
    'not_in'               => '選擇的 :attribute 無效。',
    'not_regex'            => ':attribute 格式無效。',
    'numeric'              => ':attribute 必須是數字。',
    'password'             => [
        'letters'       => ':attribute 必須包含至少一個字母。',
        'mixed'         => ':attribute 必須包含至少一個大寫字母和一個小寫字母。',
        'numbers'       => ':attribute 必須包含至少一個數字。',
        'symbols'       => ':attribute 必須包含至少一個符號。',
        'uncompromised' => '給定的 :attribute 在數據洩露中出現過。請選擇不同的 :attribute。',
    ],
    'present'              => ':attribute 字段必須存在。',
    'present_if'           => '當 :other 是 :value 時，:attribute 字段必須存在。',
    'present_unless'       => '除非 :other 是 :value，否則 :attribute 字段必須存在。',
    'present_with'         => '當 :values 存在時，:attribute 字段必須存在。',
    'present_with_all'     => '當 :values 都存在時，:attribute 字段必須存在。',
    'prohibited'           => ':attribute 字段是禁止的。',
    'prohibited_if'        => '當 :other 是 :value 時，:attribute 字段是禁止的。',
    'prohibited_unless'    => '除非 :other 在 :values 中，否則 :attribute 字段是禁止的。',
    'prohibits'            => ':attribute 字段禁止 :other 存在。',
    'regex'                => ':attribute 格式無效。',
    'required'             => ':attribute 字段是必填的。',
    'required_array_keys'  => ':attribute 必須包含 :values 的條目。',
    'required_if'          => '當 :other 是 :value 時，:attribute 字段是必填的。',
    'required_if_accepted' => '當 :other 被接受時，:attribute 字段是必填的。',
    'required_if_declined' => '當 :other 被拒絕時，:attribute 字段是必填的。',
    'required_unless'      => '除非 :other 在 :values 中，否則 :attribute 字段是必填的。',
    'required_with'        => '當 :values 存在時，:attribute 字段是必填的。',
    'required_with_all'    => '當 :values 都存在時，:attribute 字段是必填的。',
    'required_without'     => '當 :values 不存在時，:attribute 字段是必填的。',
    'required_without_all' => '當 :values 都不存在時，:attribute 字段是必填的。',
    'same'                 => ':attribute 和 :other 必須匹配。',
    'size'                 => [
        'array'   => ':attribute 必須包含 :size 項。',
        'file'    => ':attribute 必須是 :size KB。',
        'numeric' => ':attribute 必須是 :size。',
        'string'  => ':attribute 必須是 :size 個字符。',
    ],
    'starts_with'          => ':attribute 必須以以下之一開頭: :values。',
    'string'               => ':attribute 必須是字符串。',
    'timezone'             => ':attribute 必須是有效的時區。',
    'unique'               => ':attribute 已經被占用。',
    'uploaded'             => ':attribute 上傳失敗。',
    'uppercase'            => ':attribute 必須是大寫字母。',
    'url'                  => ':attribute 必須是有效的 URL。',
    'ulid'                 => ':attribute 必須是有效的 ULID。',
    'uuid'                 => ':attribute 必須是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
