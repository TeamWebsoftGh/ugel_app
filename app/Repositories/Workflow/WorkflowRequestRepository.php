<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\WorkflowRequest;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowRequestRepository;

class WorkflowRequestRepository extends BaseRepository implements IWorkflowRequestRepository
{
    public \Illuminate\Database\Eloquent\Model $model;

    public function __construct(WorkflowRequest $workflow)
    {
        parent::__construct($workflow);
        $this->model = $workflow;
    }

    public function listWorkflowRequests(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();

        $query->when(!empty($filter['filter_client']), fn ($q) => $q->where('client_id', $filter['filter_client']));
        $query->when(!empty($filter['filter_property']), fn ($q) => $q->where('property_id', $filter['filter_property']));
        $query->when(!empty($filter['filter_user']), fn ($q) => $q->where('user_id', $filter['filter_user']));
        $query->when(!empty($filter['filter_created_by']), fn ($q) => $q->where('created_by', $filter['filter_created_by']));
        $query->when(!empty($filter['filter_workflow_type']), fn ($q) => $q->where('workflow_type_id', $filter['filter_workflow_type']));
        $query->when(!empty($filter['filter_status']), fn ($q) => $q->where('status', $filter['filter_status']));

        $query->when(!empty($filter['filter_start_date']), fn ($q) => $q->whereDate('created_at', '>=', $filter['filter_start_date']));
        $query->when(!empty($filter['filter_end_date']), fn ($q) => $q->whereDate('created_at', '<=', $filter['filter_end_date']));

        return $query->orderBy($order, $sort);
    }

    public function listWorkflowRequestDetails(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = WorkflowRequestDetail::query();

        $query->when(!empty($filter['filter_workflow_request']), fn ($q) => $q->where('workflow_request_id', $filter['filter_workflow_request']));
        $query->when(!empty($filter['filter_implementor']), fn ($q) => $q->where('implementor_id', $filter['filter_implementor']));
        $query->when(!empty($filter['filter_user']), fn ($q) => $q->where('user_id', $filter['filter_user']));
        $query->when(!empty($filter['filter_workflow']), fn ($q) => $q->where('workflow_id', $filter['filter_workflow']));
        $query->when(!empty($filter['filter_workflow_type']), fn ($q) => $q->where('workflow_type_id', $filter['filter_workflow_type']));
        $query->when(!empty($filter['filter_status']), fn ($q) => $q->where('status', $filter['filter_status']));

        $query->when(!empty($filter['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $filter['date_from']));
        $query->when(!empty($filter['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $filter['date_to']));

        return $query->orderBy($order, $sort)->get();
    }
}
