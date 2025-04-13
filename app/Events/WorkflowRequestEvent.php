<?php

namespace App\Events;

use App\Models\Workflow\WorkflowRequestDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WorkflowRequestEvent
{
    public WorkflowRequestDetail $wf_request_detail;

    /**
     * Create a new event instance.
     * @param WorkflowRequestDetail $wf_request_detail
     */
    public function __construct(WorkflowRequestDetail $wf_request_detail)
    {
        $this->wf_request_detail = $wf_request_detail;
    }

}
