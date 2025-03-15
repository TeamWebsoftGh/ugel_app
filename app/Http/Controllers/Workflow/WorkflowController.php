<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Workflow\Workflow;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\IWorkflowPositionTypeService;
use App\Services\Interfaces\IWorkflowService;
use App\Services\Interfaces\IWorkflowTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use function datatables;
use function redirect;
use function request;
use function user;
use function view;

class WorkflowController extends Controller
{
    use JsonResponseTrait;

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
     * @return Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $workflow = new Workflow();
        $positionTypes = $this->positionTypeService->listActiveWorkflowPositionTypes('updated_at');
        $workflowTypes = $this->workflowTypeService->listActiveWorkflowTypes('updated_at');
        $workflows = $this->workflowService->listWorkflows('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($workflows)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($travel) {
                        return '#wf_position-content';
                    },
                ])
                ->make(true);
        }

        return view('workflow.workflows.create', compact('positionTypes', 'workflow', 'workflowTypes'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $workflow = $this->workflowService->findWorkflowById($id);
        $positionTypes = $this->positionTypeService->listActiveWorkflowPositionTypes('updated_at');
        $workflowTypes = $this->workflowTypeService->listActiveWorkflowTypes('updated_at');

        if (request()->ajax()){
            return view('workflow.workflows.edit', compact('positionTypes', 'workflow', 'workflowTypes'));
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
            'action' => 'required',
            'flow_sequence' => 'required',
            'status' => 'required',
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
