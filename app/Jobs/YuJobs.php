<?php

namespace App\Jobs;

use App\Jobs\Middleware\JobMiddleware;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class YuJobs implements ShouldQueue
    //,ShouldBeUnique,ShouldBeEncrypted
{
    use Queueable, Batchable;


    public function middleware(): array
    {
        return [new SkipIfBatchCancelled,new JobMiddleware()];
    }

    public function handle(User $user, $params = '')
    {
//        $this->prependToChain([
//            new ChainJobs()
//        ]);
        $this->batch()->cancel();
        $this->batch()?->add(new ChainJobs());

        if($this->batch()->canceled()){
            Log::emergency('取消批处理');
        }
        Log::emergency('1已触发队列Yu' . $user->id);
    }
}