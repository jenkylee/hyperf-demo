<?php

namespace App\Task;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * Class FooTask
 *
 * @package App\Task
 *
 * @Crontab(name="Foo", rule="* * * * * *", callback="execute", memo="这个是示例定时任务")
 */
class FooTask
{
    /**
     * @Inject()
     * @var StdoutLoggerInterface
     */
    private $logger;

    public function execute()
    {
        $this->logger->info(date('Y-m-d H:i:s', time()));
    }
}