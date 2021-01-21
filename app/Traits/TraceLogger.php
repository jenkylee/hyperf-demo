<?php
declare(strict_types=1);

namespace App\Traits;

use Hyperf\Utils\Context;

trait TraceLogger
{
    public function traceLogger($name = 'app')
    {
        $trace_id = Context::get('traceId');
        if ($trace_id) {
            $name = "[TRACE:$trace_id] " . $name;
        }

        return logger($name);
    }
}