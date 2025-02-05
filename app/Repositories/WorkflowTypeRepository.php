<?php

namespace App\Repositories;

use App\Models\Workflow\WorkflowType;
use App\Repositories\Interfaces\IWorkflowTypeRepository;
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
    public function listWorkflowTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the WorkflowType
     *
     * @param array $data
     *
     * @return WorkflowType
     */
    public function createWorkflowType(array $data): WorkflowType
    {
        return $this->create($data);
    }


    /**
     * Find the WorkflowType by id
     *
     * @param int $id
     *
     * @return WorkflowType
     */
    public function findWorkflowTypeById(int $id): WorkflowType
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update WorkflowType
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateWorkflowType(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteWorkflowType(int $id): bool
    {
        return $this->delete($id);
    }
}
