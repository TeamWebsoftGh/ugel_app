<?php

namespace App\Repositories;

use App\Models\Task\Task;
use App\Models\Workflow\WorkflowPosition;
use App\Repositories\Interfaces\ITaskRepository;
use App\Traits\UploadableTrait;
use App\Utilities\WorkflowUtil;
use Illuminate\Support\Collection;

/**
 *
 */
class TaskRepository extends BaseRepository implements ITaskRepository
{
    use UploadableTrait, WorkflowUtil;
    /**
     * TaskRepository constructor.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->model = $task;
    }

    /**
     * List all the Tasks
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $tasks
     */
    public function listTasks(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = Task::query();
        $res = WorkflowPosition::where(['is_active' => 1, 'employee_id' => employee()->id])->get();

        if(!user()->can('read-all-tasks') && count($res) < 1)
        {
            $result = $result->where(function ($query) {
                return $query->whereHas('assignee', function ($query) {
                    return $query->where('id', '=', user()->id);
                })->orWhere('user_id', user()->id);
            });
        }

        if(count($res) > 0)
        {
            $r = $res->first();
            $f = $this->getFieldName($r->workflowPositionType->code);
            $result = $result->where(function ($query) use ($r, $f){
                return $query->whereHas('assignee', function ($query) use ($r, $f){
                    return $query->where($f, '=', $r->subject_id);
                })->orWhere('user_id', user()->id);
            });
        }

        if (!empty($params['filter_department']))
        {
            $result = $result->whereHas('assignee', function ($query) use($params) {
                return $query->where('department_id', '=', $params['filter_department']);
            });
        }

        if (!empty($params['filter_subsidiary']))
        {
            $result = $result->whereHas('assignee', function ($query) use($params) {
                return $query->where('subsidiary_id', '=', $params['filter_subsidiary']);
            });
        }

        if (!empty($params['filter_status']))
        {
            $result = $result->where('status_id', $params['filter_status']);
        }

        if (!empty($params['filter_assignee']))
        {
            $result = $result->where('assignee_id', $params['filter_assignee']);
        }

        if (!empty($params['filter_assigned_by']))
        {
            $result = $result->where('user_id', $params['filter_assigned_by']);
        }

        if (!empty($params['filter_start_date']))
        {
            $result = $result->where('start_date', '>=', $params['filter_start_date']);
        }

        if (!empty($params['filter_end_date']))
        {
            $result = $result->where('start_date', '<=', $params['filter_end_date']);
        }

        if (!empty($id))
        {
            $result->where('parent_id','=', $id);
        }

        return $result->orderBy($order, $sort)->get();
    }


    /**
     * Create the Task
     *
     * @param array $data
     *
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return $this->create($data);
    }

    /**
     * Find the Task by id
     *
     * @param int $id
     *
     * @return Task
     */
    public function findTaskById(int $id): Task
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Task
     *
     * @param array $params
     *
     * @param Task $task
     * @return bool
     */
    public function updateTask(array $params, Task $task)
    {
        return $task->update($params);
    }

    /**
     * @param Task $task
     * @return bool|null
     * @throws \Exception
     */
    public function deleteTask(Task $task)
    {
        return $task->delete();
    }

    private function getFieldName($type)
    {
        switch ($type) {
            case "general-manager":
                return "subsidiary_id";
                break;
            case "hod":
                return "department_id";
                break;
            case "branch-manager":
                return "branch_id";
                break;
            default:
                return "supervisor_id";
        }
    }
}
