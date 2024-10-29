<?php

namespace App\Http\Controllers\Task;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Audit\LogActivity;
use App\Models\Task\CheckListItem;
use App\Models\Task\Timesheet;
use App\Services\Interfaces\ITaskService;
use App\Traits\JsonResponseTrait;
use App\Traits\WorkflowUtil;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function view;

class TaskController extends Controller
{
    use WorkflowUtil;

    use JsonResponseTrait;

    private ITaskService $taskService;

    Public function __construct(ITaskService $task)
    {
        $this->taskService = $task;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $tasks = $this->taskService->listTasks($data);

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        return view("tasks.index", compact("tasks", "data"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function pending(Request $request)
    {
        $data = $request->all();
        $tasks = $this->taskService->listTasks($data)->whereNotIn('status_id', [6]);

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        if (request()->ajax())
        {
            return datatables()->of($tasks)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('status', function ($row)
                {
                    return $row->taskStatus->name ?? ' ';
                })
                ->addColumn('task_date', function ($row)
                {
                    return 'Start date: '.$row->start_date.'<br/>Due Date: '.$row->due_date;
                })
                ->addColumn('weightage', function ($row)
                {
                    return 'Total Weight: '.$row->total_weightage.'<br/>Employee Score: '.$row->employee_score??"N/A";
                })
                ->addColumn('employee', function ($row)
                {
                    return $row->assignee->fullname.'<br/>'.$row->assignee->subsidiary->name;
                })
                ->addColumn('user', function ($row)
                {
                    return $row->user->fullname.'<br/>'.$row->user->subsidiary->name;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-success btn-sm"><i class="dripicons-preview"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action', 'employee', 'task_date', 'weightage'])
                ->make(true);
        }

        return view("tasks.index", compact("tasks", "data"));

    }

    public function myTasks()
    {
        $tasks = $this->taskService->listTasks()->where('assignee_id', '==', user()->id);
        $user = user();

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        return view("tasks.index", compact("tasks", "data", "user"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $data = $this->taskService->getCreateTask();
        $data['task']->total_weightage = 10;
        $data['task']->priority_id = 2;
        return view("tasks.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(TaskRequest $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $task = $this->taskService->findTaskById($request->input("id"));
            $results = $this->taskService->updateTask($data, $task);
        }else{
            $results = $this->taskService->createTask($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('tasks.show', $results->data->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $task = $this->taskService->findTaskById($id);

        if(!$this->canAccessItem($task->assignee_id, $task->user_id) && !user()->hasPermission('read-all-tasks'))
        {
            abort(403);
        }

        $logs = LogActivity::orderBy('id', 'desc')
            ->where([
                ['subject_type', 'App\Models\Task\Task'],
                ['subject_id', $task->id],
            ])->get();
        $files = $task->documents()->orderByDesc('created_at')->get();
        $activities = $task->timesheets()->orderByDesc('start_time')->get();
        $objectives = $task->objectives()->orderByDesc('created_at')->get();
        $activity = new Timesheet();
        $objective = new CheckListItem();
        return view("portal.tasks.show", compact("task", "logs", 'files', 'activity', 'activities', 'objectives', 'objective'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $task = $this->taskService->findTaskById($id);
        if(!$this->canAccessItem($task->assignee_id, $task->user_id))
        {
            abort(403);
        }
        $data = $this->taskService->getCreateTask();
        $data['task'] = $task;
        return view("tasks.create", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function postComment(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
            'task_id' => 'required'
        ]);

        $task = $this->taskService->findTaskById($request->task_id);
        $data = $request->except('_token', '_method');
        $results = $this->taskService->postComment($data, $task);

        if($request->ajax()){
            return $this->responseJson($results);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->back();
    }

    public function deleteComment($task_id, $id)
    {
        $task = $this->taskService->findTaskById($task_id);
        $comment = $task->comments()->findOrFail($id);

        $result = $this->taskService->deleteComment($comment, $task);

        return $this->responseJson($result);
    }

    public function uploadFile(Request $request)
    {
        $validatedData = $request->validate([
            'task_files' => 'required',
            'task_id' => 'required'
        ]);

        $task = $this->taskService->findTaskById($request->task_id);
        $results = $this->taskService->uploadDocument($request->except('_token', '_method'), $task);

        if($request->ajax()){
            return $this->responseJson($results);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->back();
    }

    public function deleteDocument($task_id, $id)
    {
        $task = $this->taskService->findTaskById($task_id);

        $document = $task->documents()->findOrFail($id);

        $result = $this->taskService->deleteDocument($document, $task);
        return $this->responseJson($result);
    }

    public function downloadDocument($task_id, $id)
    {
        $task = $this->taskService->findTaskById($task_id);

        $document = $task->documents()->findOrFail($id);

        $result = $this->taskService->deleteDocument($document, $task);
        return $this->responseJson($result);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $task = $this->taskService->findTaskById($id);

        $result = $this->taskService->changeStatus($request->status,$task);

        return $this->responseJson($result);
    }
}
