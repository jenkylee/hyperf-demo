<?php
declare(strict_types=1);

namespace App\Service;

use App\Job\PaperZipJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

/**
 * 投递消息类
 *
 * Class QueueService
 *
 * @package App\Services
 */
class QueueService
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    /**
     * 生产试卷压缩消息
     *
     * @param array $params 数据
     * @param int $delay 延时时间 单位秒
     * @return bool
     */
    public function pushPaperZip(array $params, int $delay = 0) : bool
    {
        return $this->driver->push(new PaperZipJob($params), $delay);
    }
}