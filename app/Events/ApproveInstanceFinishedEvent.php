<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApproveInstanceFinishedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $modelType;
    public $modelId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(String $modelType, Int $modelId)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
    }
}
