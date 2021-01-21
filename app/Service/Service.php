<?php

namespace App\Service;

use App\Traits\TraceLogger;
class Service
{
    use TraceLogger;

    /**
     * @var int
     */
    protected $perPage = 15;
}