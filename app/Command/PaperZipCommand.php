<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command
 */
class PaperZipCommand extends HyperfCommand
{
    /**
     * 执行的命令行
     *
     * @var string
     */
    protected $name = 'paper:zip';

    public function configure()
    {
        parent::configure();
        $this->setDescription('试卷文件压缩打包命令');
    }

    public function handle()
    {
        $arguments = $this->input->getArguments();
        //$level = (int)$arguments['level'] ?? -1;
        //$grade = (int)$arguments['grade'] ?? 0;
        //$limit = (int)$arguments['limit'] ?? 0;
    }

    protected function getArguments()
    {
        return [
            ['level', InputArgument::OPTIONAL, '层级：0-自定义 1-基础 2-提高'],
            ['grade', InputArgument::OPTIONAL, '年级：7、8、9'],
            ['limit', InputArgument::OPTIONAL, '限制个数'],
        ];
    }
}
