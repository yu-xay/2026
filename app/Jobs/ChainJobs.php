<?php

namespace App\Jobs;

use App\Jobs\Middleware\JobMiddleware;
use DateTime;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class    ChainJobs implements ShouldQueue
{
    use Queueable, Batchable;

    public int $maxExceptions = 1;
    public int $timeout = 30;
    public int $tries = 1;
    public bool $failOnTimeout = false;
    public function backoff(): array
    {
        return [1, 5, 10];
    }
    public function __construct()
    {
     //   $this->onQueue('high')->onConnection('redis');
    }

    public function middleware(): array
    {
        dd(3);
        return [new JobMiddleware()];
    }

    public function retryUntil(): DateTime
    {
        return now()->addSecond(30);
    }


    public function handle()
    {
        sleep(10);
        Log::info(ChainJobs::class);
        // $this->fail('失败了');
    }
    /**
     * Handle a job failure.
     */
    public function failed(?\Throwable $exception): void
    {
        // Send user notification of failure, etc...
    }
}
