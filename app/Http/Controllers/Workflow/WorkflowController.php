<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Workflow\Workflow;
use App\Services\Workflow\Interfaces\IWorkflowPositionTypeService;
use App\Services\Workflow\Interfaces\IWorkflowService;
use App\Services\Workflow\Interfaces\IWorkflowTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    private IWorkflowPositionTypeService $positionTypeService;
    private IWorkflowService $workflowService;
    private IWorkflowTypeService $workflowTypeService;

    /**
     * Create a new controller instance.
     *
     * @param IWorkflowPositionTypeService $positionType
     * @param IWorkflowService $workflow
     */
    public function __construct(IWorkflowPositionTypeService $positionType, IWorkflowService $workflow, IWorkflowTypeService $workflowType)
    {
        parent::__construct();
        $this->workflowService = $workflow;
        $this->positionTypeService = $positionType;
        $this->workflowTypeService = $workflowType;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Application|Factory|RedirectResponse|View
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $items = $this->workflowService->listWorkflows($request->all());
            return datatables()->of($items)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('workflow_type_name', fn($row) => $row->workflowType->name)
                ->addColumn('workflow_position_name', fn($row) => $row->workflowPositionType->name)
                ->addColumn('flow_sequence', fn($row) => "Stage ".$row->flow_sequence)
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "workflow-position-types"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow.workflows.index');
    }

    public function create()
    {
        $workflow = new Workflow();
        $workflow->is_active = 1;
        $flowSequenceOptions = collect(range(1, 10))->mapWithKeys(function ($i) {
            return [$i => "Stage $i"];
        });
        $positionTypes = $this->positionTypeService->listActiveWorkflowPositionTypes();
        $workflowTypes = $this->workflowTypeService->listActiveWorkflowTypes();

        if (request()->ajax()){
            return view('workflow.workflows.edit', compact('positionTypes', 'workflow', 'workflowTypes', 'flowSequenceOptions'));
        }

        return redirect()->route('workflows.workflows.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function edit($id)
    {
        $workflow = $this->workflowService->findWorkflowById($id);
        $positionTypes = $this->positionTypeService->listActiveWorkflowPositionTypes();
        $workflowTypes = $this->workflowTypeService->listActiveWorkflowTypes();
        $flowSequenceOptions = collect(range(1, 10))->mapWithKeys(function ($i) {
            return [$i => "Stage $i"];
        });

        if (request()->ajax()){
            return view('workflow.workflows.edit', compact('positionTypes', 'workflow', 'workflowTypes', 'flowSequenceOptions'));
        }

        return redirect()->route('workflows.workflows.index');
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
            'workflow_name' => 'required',
            'workflow_type' => 'required',
            'workflow_position_type' => 'required',
            'action_type' => 'required',
            'flow_sequence' => 'required',
            'is_active' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['workflow_type_id'] =  $data['workflow_type'];
        $data['workflow_position_type_id'] =  $data['workflow_position_type'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $workflow = $this->workflowService->findWorkflowById($request->input("id"));
            $results = $this->workflowService->updateWorkflow($data, $workflow);
        }else{
            $results = $this->workflowService->createWorkflow($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('workflows.workflows.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $workflow = $this->workflowService->findWorkflowById($id);

        $result = $this->workflowService->deleteWorkflow($workflow);

        return $this->responseJson($result);
    }
}
