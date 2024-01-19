<?php

namespace App\Events;

use App\Models\ApproveInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApproveInstanceFinishedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $model;
    public $approveInstance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, ApproveInstance $approveInstance)
    {
        $this->model = $model;
        $this->approveInstance = $approveInstance;
    }
}
