<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

$enable = env('LOG_DEBUG_ENABLE', false);
if ($enable) {
    $level = Monolog\Logger::DEBUG;
} else {
    $level = Monolog\Logger::INFO;
}

return [
    'default' => [
        'handler' => [
            'class' => Monolog\Handler\RotatingFileHandler::class,
            'constructor' => [
                'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                'level' => $level,
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => '[lf-'.env('APP_ID', 1).']['.env('APP_CNAME', 'default').'][%datetime%] %channel%.%level_name%: %message% %context% %extra%'."\n",
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
