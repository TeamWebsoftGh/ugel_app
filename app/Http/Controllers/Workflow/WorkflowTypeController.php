<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\CustomerService\MaintenanceRequest;
use App\Models\Workflow\WorkflowType;
use App\Services\Interfaces\IWorkflowTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


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
        if (request()->ajax())
        {
            $workflowTypes = $this->workflowTypeService->listWorkflowTypes('updated_at');

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
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-workflow-types'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-workflow-types'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow.workflow-types.index');
    }

    public function create()
    {
        $workflowType = new WorkflowType();
        $models = [
            MaintenanceRequest::class => 'Maintenance',
        ];

        if (request()->ajax()){
            return view('workflow.workflow-types.edit', compact('workflowType', 'models'));
        }

        return redirect()->route('workflows.workflow-types.index');
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

    public function edit($id)
    {
        $workflowType = $this->workflowTypeService->findWorkflowTypeById($id);
        $models = [
            MaintenanceRequest::class => 'Maintenance',
        ];

        if (request()->ajax()){
            return view('workflow.workflow-types.edit', compact('workflowType', 'models'));
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
            'code' => 'required',
            'subject_type' => 'required',
            'sort_order' => 'required',
            'is_active' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

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
