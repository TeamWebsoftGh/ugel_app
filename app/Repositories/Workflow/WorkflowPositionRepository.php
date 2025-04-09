<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\WorkflowPosition;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionRepository;
use Illuminate\Support\Collection;

class WorkflowPositionRepository extends BaseRepository implements IWorkflowPositionRepository
{
    /**
     * WorkflowPosition Repository
     *
     * @param WorkflowPosition $workflowPosition
     */
    public \Illuminate\Database\Eloquent\Model $model;
    public function __construct(WorkflowPosition $workflowPosition)
    {
        parent::__construct($workflowPosition);
        $this->model = $workflowPosition;
    }

    /**
     * List all Companies
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listWorkflowPositions(array $filter=[], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();
        $pt = $filter['filter_position_type']??null;

        $query = $query->when($pt, function ($q, $pt) {
            return $q->where('position_type_id',  $pt);
        });
        return $query->orderBy($order, $sort);
    }
}
