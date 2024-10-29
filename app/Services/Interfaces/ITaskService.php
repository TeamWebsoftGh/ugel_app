<?php

namespace App\Services\Interfaces;

use App\Models\Task\CheckListItem;
use App\Models\Task\Task;
use App\Models\Task\TaskComment;
use App\Models\Task\Timesheet;
use App\Services\Helpers\Response;
use Illuminate\Support\Collection;

interface ITaskService extends IBaseService
{
    public function listTasks(array $filter, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createTask(array $params);

    public function findTaskById(int $id) : Task;

    public function updateTask(array $params, Task $task);

    public function changeStatus(int $status, Task $task);

    public function deleteTask(Task $task);

    public function getCreateTask();

    public function uploadDocument(array $data, Task $task);

    public function postComment(array $data, Task $task);

    public function deleteComment(TaskComment $comment, Task $task): Response;

    public function saveActivity(array $data, Task $task): Response;

    public function deleteActivity(Timesheet $activity, Task $task): Response;

    public function saveObjective(array $data, Task $task): Response;

    public function deleteObjective(CheckListItem $checkListItem, Task $task): Response;
}
