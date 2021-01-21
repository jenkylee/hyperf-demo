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
    //是否开启定时任务
    'enable' => (bool)env("CRONTAB_ENABLe", true),
    //通过配置文件定义定时任务
    'crontab' => [
        //Callback类型定时任务(默认)
        //(new Crontab())->setName('Foo')->setRule('* * * × ×')->setCallback([App\Task\FooTask::class,'execute'])->setMemo('这是一个示例的定时任务'),
    ],
];
