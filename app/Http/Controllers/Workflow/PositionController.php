<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\IWorkflowPositionService;
use App\Traits\WorkflowUtil;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class PositionController extends Controller
{
    use WorkflowUtil;

    private IWorkflowPositionService $workflowPositionService;

    /**
     * Create a new controller instance.
     *
     * @param IWorkflowPositionService $workflowPositionService
     */
    public function __construct(IWorkflowPositionService $workflowPositionService)
    {
        parent::__construct();
        $this->workflowPositionService = $workflowPositionService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $workflowPosition = new WorkflowPosition();
        $positionTypes = WorkflowPositionType::where('is_active', 1)->get();
        $workflowPositions = $this->workflowPositionService->listWorkflowPositions('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($workflowPositions)
                ->setRowId(function ($award)
                {
                    return $award->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($travel) {
                        return '#wf_position-content';
                    },
                ])
                ->make(true);
        }

        return view('workflow.positions.create', compact('positionTypes', 'workflowPosition'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $positionTypes = WorkflowPositionType::where('is_active', 1)->get();
        $workflowPosition = $this->workflowPositionService->findWorkflowPositionById($id);

        if (request()->ajax()){
            return view('workflow.positions.edit', compact('positionTypes', 'workflowPosition'));
        }

        return redirect()->route('workflows.positions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'position_name' => 'required',
            'workflow_position_type' => 'required',
            'employee_id' => 'required',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['user_id'] = $data['employee_id'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $complaint = $this->workflowPositionService->findWorkflowPositionById($request->input("id"));
            $results = $this->workflowPositionService->updateWorkflowPosition($data, $complaint);
        }else{
            $results = $this->workflowPositionService->createWorkflowPosition($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('workflows.positions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $workflowType = $this->workflowPositionService->findWorkflowPositionById($id);

        $result = $this->workflowPositionService->deleteWorkflowPosition($workflowType);

        return $this->responseJson($result);
    }
}
