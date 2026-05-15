<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class JobMiddleware
{
    /**
     * Process the queued job.
     *
     * @param \Closure(object): void $next
     */
    public function handle(object $job, Closure $next): void
    {
        Log::info('队列中间件 =>' . get_class($job));
        $next($job);
    }
}
