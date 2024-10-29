<?php

namespace App\Repositories;

use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Interfaces\IWorkflowPositionTypeRepository;
use Illuminate\Support\Collection;

class WorkflowPositionTypeRepository extends BaseRepository implements IWorkflowPositionTypeRepository
{
    /**
     * WorkflowPositionType Repository
     *
     * @param WorkflowPositionType $workflowPositionType
     */
    public $model;
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
    public function listWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the WorkflowPositionType
     *
     * @param array $data
     *
     * @return WorkflowPositionType
     */
    public function createWorkflowPositionType(array $data): WorkflowPositionType
    {
        return $this->create($data);
    }


    /**
     * Find the WorkflowPositionType by id
     *
     * @param int $id
     *
     * @return WorkflowPositionType
     */
    public function findWorkflowPositionTypeById(int $id): WorkflowPositionType
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update WorkflowPositionType
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateWorkflowPositionType(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteWorkflowPositionType(int $id): bool
    {
        return $this->delete($id);
    }
}
