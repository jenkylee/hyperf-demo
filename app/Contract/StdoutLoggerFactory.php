<?php
declare(strict_types=1);
/**
 * 使用monolog输出终端日志
 */
namespace App\Contract;

use App\Utils\Log;
use Psr\Container\ContainerInterface;

class StdoutLoggerFactory
{
    public function __invoke (ContainerInterface $container)
    {
        return Log::get('sys');
    }
}