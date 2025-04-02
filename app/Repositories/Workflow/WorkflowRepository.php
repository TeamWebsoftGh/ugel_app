<?php

namespace App\Repositories\Workflow;

use App\Models\Workflow\Workflow;
use App\Repositories\BaseRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowRepository;
use Illuminate\Support\Collection;

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
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflows(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the Workflow
     *
     * @param array $data
     *
     * @return Workflow
     */
    public function createWorkflow(array $data): Workflow
    {
        return $this->create($data);
    }


    /**
     * Find the Workflow by id
     *
     * @param int $id
     *
     * @return Workflow
     */
    public function findWorkflowById(int $id): Workflow
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Workflow
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateWorkflow(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteWorkflow(int $id): bool
    {
        return $this->delete($id);
    }
}
