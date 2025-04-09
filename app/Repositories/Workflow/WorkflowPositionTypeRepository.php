<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionTypeRepository;
use Illuminate\Support\Collection;

class WorkflowPositionTypeRepository extends BaseRepository implements IWorkflowPositionTypeRepository
{
    /**
     * WorkflowPositionType Repository
     *
     * @param WorkflowPositionType $workflowPositionType
     */
    public \Illuminate\Database\Eloquent\Model $model;
    public function __construct(WorkflowPositionType $workflowPositionType)
    {
        parent::__construct($workflowPositionType);
        $this->model = $workflowPositionType;
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
    public function listWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }
}
