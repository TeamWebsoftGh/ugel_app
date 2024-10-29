<?php

namespace App\Http\Controllers\Task;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ITaskService;
use App\Traits\WorkflowUtil;
use Illuminate\Http\Request;

class TaskDetailController extends Controller
{
    use JsonResponseTrait, WorkflowUtil;

    private ITaskService $taskService;

    Public function __construct(ITaskService $task)
    {
        $this->taskService = $task;
    }

    public function storeObjective(Request $request)
    {
        $task = $this->taskService->findTaskById($request->input("task_id"));

        $validatedData = $request->validate([
            'objective' => 'required',
        ]);

        $data = $request->except('_token', '_method');
        $data['name'] = $data['objective'];
        $results = $this->taskService->saveObjective($data, $task);

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('tasks.show', $task->id);
    }

    public function edit(Request $request, $taskId, $id)
    {
        $task = $this->taskService->findTaskById($taskId);
        $objective = $task->timesheets()->findOrFail($id);

        if ($request->ajax()){
            return view('tasks.partials.edit-objective', compact('objective', 'task'));
        }
    }

    public function deleteObjective($task_id, $id)
    {
        $task = $this->taskService->findTaskById($task_id);

        $objective = $task->objectives()->findOrFail($id);

        $result = $this->taskService->deleteObjective($objective, $task);
        return $this->responseJson($result);
    }
}
