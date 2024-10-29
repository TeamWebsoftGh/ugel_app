<?php

namespace App\Repositories\Interfaces;

use App\Models\Task\Task;
use Illuminate\Support\Collection;

interface ITaskRepository extends IBaseRepository
{
    public function listTasks(array $params = null, string $order = 'updated_at', string $sort = 'desc') : Collection;

    public function createTask(array $params) : Task;

    public function updateTask(array $params, Task $Task);

    public function findTaskById(int $id) : Task;

    public function deleteTask(Task $Task);
}
