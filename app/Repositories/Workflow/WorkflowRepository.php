<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\Workflow;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowRepository;

class WorkflowRepository extends BaseRepository implements IWorkflowRepository
{
    /**
     * Workflow Repository
     *
     * @param Workflow $workflow
     */
    public \Illuminate\Database\Eloquent\Model $model;
    public function __construct(Workflow $workflow)
    {
        parent::__construct($workflow);
        $this->model = $workflow;
    }

    /**
     * List all Companies
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return mixed
     */
    public function listWorkflows(array $filter=[], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();

        $query->when(!empty($filter['filter_workflow_type']), function ($q) use ($filter) {
            $q->where('workflow_type_id', $filter['filter_workflow_type']);
        });

        $query =$query->orderBy("workflow_type_id", "desc");

        return $query->orderBy($order, $sort);
    }
}
