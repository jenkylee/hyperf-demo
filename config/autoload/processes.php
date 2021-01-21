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

return [
    Hyperf\AsyncQueue\Process\ConsumerProcess::class, // 启用消费队列进程
    Hyperf\Crontab\Process\CrontabDispatcherProcess::class, // 启用任务调度器进程
];
