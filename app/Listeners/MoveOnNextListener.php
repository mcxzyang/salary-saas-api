<?php

namespace App\Listeners;

use App\Events\ApproveInstanceFinishedEvent;
use Illuminate\Support\Facades\Log;

class MoveOnNextListener
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ApproveInstanceFinishedEvent  $event
     *
     * @return void
     */
    public function handle(ApproveInstanceFinishedEvent $event): void
    {
        $modelType = $event->modelType;
        $modelId = $event->modelId;

        Log::info('收到事件，modelType:'.$modelType.', modelId:'.$modelId);
    }
}
