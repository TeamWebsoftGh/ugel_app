<?php

namespace App\Events;

use App\Models\WorkflowRequestDetail;

class WorkflowStatusChanged
{
    public $workflowRequestDetail;

    /**
     * Create a new event instance.
     * @param WorkflowRequestDetail $workflowRequestDetail
     */
    public function __construct(WorkflowRequestDetail $workflowRequestDetail)
    {
        $this->workflowRequestDetail = $workflowRequestDetail;
    }

}
