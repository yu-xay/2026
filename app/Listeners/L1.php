<?php

namespace App\Listeners;

use App\Events\PodcastProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class L1 implements ShouldQueue
{
    public function shouldQueue(PodcastProcessed $event): bool
    {

       return true;
    }


    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function __invoke(PodcastProcessed $event): void
    {

        //
        Log::info('x2'.$event->params);

        dd(3333);
    }
}
