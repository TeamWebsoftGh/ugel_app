<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use App\Models\Workflow\WorkflowType;
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

class WorkflowTypeController extends Controller
{
    private IWorkflowTypeService $workflowTypeService;

    /**
     * Create a new controller instance.
     *
     * @param IWorkflowTypeService $workflowTypeService
     */
    public function __construct(IWorkflowTypeService $workflowTypeService)
    {
        parent::__construct();
        $this->workflowTypeService = $workflowTypeService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $workflowType = new WorkflowType();
        $workflowTypes = $this->workflowTypeService->listWorkflowTypes('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($workflowTypes)
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

        return view('workflow.workflow-types.create', compact( 'workflowType'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $workflowType = $this->workflowTypeService->findWorkflowTypeById($id);

        if (request()->ajax()){
            return view('workflow.workflow-types.edit', compact('workflowType'));
        }

        return redirect()->route('workflows.workflow-types.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'position_code' => 'required',
            'sort_order' => 'required',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['code'] = $data['position_code'];
        $data['is_active'] = $data['status'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $complaint = $this->workflowTypeService->findWorkflowTypeById($request->input("id"));
            $results = $this->workflowTypeService->updateWorkflowType($data, $complaint);
        }else{
            $results = $this->workflowTypeService->createWorkflowType($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('workflows.workflow-types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $workflowType = $this->workflowTypeService->findWorkflowTypeById($id);

        $result = $this->workflowTypeService->deleteWorkflowType($workflowType);

        return $this->responseJson($result);
    }
}
