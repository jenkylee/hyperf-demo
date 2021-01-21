<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class StopCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('stop');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Stop Server Command');
    }

    public function handle()
    {
        $config = $this->container->get(ConfigInterface::class);
        $serverConfig = $config->get('server');
        if (file_exists($serverConfig['settings']['pid_file'])) {
            $pid = (int)file_get_contents($serverConfig['settings']['pid_file']);
            posix_kill($pid, SIGTERM);
            $this->line('stop server success!!!', 'info');
        } else {
            $this->line('file not exit, stop server fail!!!', 'error');
        }
    }
}
