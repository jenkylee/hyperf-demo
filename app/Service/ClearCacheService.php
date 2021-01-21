<?php

namespace App\Service;

use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;

class ClearCacheService
{
    /**
     * @Inject()
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function flushPaperCache($paperId)
    {
        $this->dispatcher->dispatch(new DeleteListenerEvent('paper-update', [$paperId]));

        return true;
    }
}