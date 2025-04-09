<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\WorkflowType;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowTypeRepository;
use Illuminate\Support\Collection;

class WorkflowTypeRepository extends BaseRepository implements IWorkflowTypeRepository
{
    /**
     * WorkflowType Repository
     *
     * @param WorkflowType $workflowType
     */
    public \Illuminate\Database\Eloquent\Model $model;
    public function __construct(WorkflowType $workflowType)
    {
        parent::__construct($workflowType);
        $this->model = $workflowType;
    }

    /**
     * List all Companies
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflowTypes(string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }
}
