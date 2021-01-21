<?php
declare(strict_types=1);

return [
    'app_id' => env('APP_ID', 16),
    'app_log_enable' => env('APP_LOG_ENABLE', false),
    'request_log_enable' => env('REQUEST_LOG_ENABLE', false),
    'response_log_enable' => env('RESPONSE_LOG_ENABLE', false),
    'auth_enable' => env('AUTH_ENABLE', false),
    'sql_log_enable' => env('SQL_LOG_ENABLE', false),
    'internal_ip' => env('INTERNAL_IP', null),
    'callers' => [
        'ck-UC' => '用户中心',
        'ck-BS' => '业务服务',
        'ck-CS' => '云学校'
    ]
];