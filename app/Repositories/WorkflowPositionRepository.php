<?php

namespace App\Repositories;

use App\Models\Workflow\WorkflowPosition;
use App\Repositories\Interfaces\IWorkflowPositionRepository;
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
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflowPositions(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the WorkflowPosition
     *
     * @param array $data
     *
     * @return WorkflowPosition
     */
    public function createWorkflowPosition(array $data): WorkflowPosition
    {
        return $this->create($data);
    }


    /**
     * Find the WorkflowPosition by id
     *
     * @param int $id
     *
     * @return WorkflowPosition
     */
    public function findWorkflowPositionById(int $id): WorkflowPosition
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update WorkflowPosition
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateWorkflowPosition(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteWorkflowPosition(int $id): bool
    {
        return $this->delete($id);
    }
}
