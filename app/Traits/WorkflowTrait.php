<?php

namespace App\Traits;



use App\Models\Workflow\WorkflowRequestDetail;

trait WorkflowTrait
{
    public function getCanApproveAttribute()
    {
//        $workflowRequests = WorkflowRequestDetail::where(['workflow_request_details.implementor_id' => user()->id])
//            ->join('workflow_requests as b','workflow_request_details.workflow_request_id','b.id')
//            ->where(['b.workflow_requestable_type' => get_class($this), 'b.workflow_requestable_id' => $this->id])
//            ->whereIn('workflow_request_details.status', ['PENDING'])
//            ->whereIn('b.status', ['PENDING'])
//            ->selectRaw('workflow_request_details.*,b.workflow_requestable_type, b.workflow_requestable_id')
//            ->first();

        if ($this->WorkflowRequestDetail)
        {
            return true;
        }

        return false;
    }

    public function getWorkflowRequestDetailAttribute()
    {
        return WorkflowRequestDetail::where(['workflow_request_details.implementor_id' => user()->id])
            ->join('workflow_requests as b','workflow_request_details.workflow_request_id','b.id')
            ->where(['b.workflow_requestable_type' => get_class($this), 'b.workflow_requestable_id' => $this->id])
            ->whereIn('workflow_request_details.status', ['PENDING'])
            ->whereIn('b.status', ['PENDING'])
            ->selectRaw('workflow_request_details.*,b.workflow_requestable_type, b.workflow_requestable_id')
            ->first();
    }

    public function getAllWorkflowRequestDetailAttribute()
    {
        return WorkflowRequestDetail::where(['workflow_request_details.implementor_id' => user()->id])
            ->join('workflow_requests as b','workflow_request_details.workflow_request_id','b.id')
            ->where(['b.workflow_requestable_type' => get_class($this), 'b.workflow_requestable_id' => $this->id])
            ->whereIn('workflow_request_details.status', ['PENDING'])
            ->whereIn('b.status', ['PENDING'])
            ->selectRaw('workflow_request_details.*,b.workflow_requestable_type, b.workflow_requestable_id');
    }

    public function getCanViewAttribute()
    {
        $canView =  WorkflowRequestDetail::where(['workflow_request_details.implementor_id' => user()->id])
            ->join('workflow_requests as b','workflow_request_details.workflow_request_id','b.id')
            ->where(['b.workflow_requestable_type' => get_class($this), 'b.workflow_requestable_id' => $this->id])
            ->selectRaw('workflow_request_details.*,b.workflow_requestable_type, b.workflow_requestable_id')
            ->first();
        if ($canView) return true;
        return false;
    }
}
