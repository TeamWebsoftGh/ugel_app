<?php

namespace App\Http\Controllers\Task;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ITaskService;
use App\Traits\WorkflowUtil;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    use JsonResponseTrait, WorkflowUtil;

    private ITaskService $taskService;

    Public function __construct(ITaskService $task)
    {
        $this->taskService = $task;
    }

    public function store(Request $request)
    {
        $task = $this->taskService->findTaskById($request->input("task_id"));

        $validatedData = $request->validate([
            'task_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'note' => 'required',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method');
        if(isset($data['objective'])){
            $data['check_list_item_id'] = $data['objective'];
        }

        $results = $this->taskService->saveActivity($data, $task);

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
        $activity = $task->timesheets()->findOrFail($id);
        $objectives = $task->objectives;

        if ($request->ajax()){
            return view('tasks.partials.edit-activity', compact('activity', 'task', 'objectives'));
        }
    }

    public function delete($task_id, $id)
    {
        $task = $this->taskService->findTaskById($task_id);

        $activity = $task->timesheets()->findOrFail($id);

        $result = $this->taskService->deleteActivity($activity, $task);
        return $this->responseJson($result);
    }
}
