<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Workflow\WorkflowPositionType;
use App\Services\Workflow\Interfaces\IWorkflowPositionTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


class PositionTypeController extends Controller
{
    private IWorkflowPositionTypeService $workflowPositionTypeService;

    /**
     * Create a new controller instance.
     *
     * @param IWorkflowPositionTypeService $workflowPositionTypeService
     */
    public function __construct(IWorkflowPositionTypeService $workflowPositionTypeService)
    {
        parent::__construct();
        $this->workflowPositionTypeService = $workflowPositionTypeService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax())
        {
            $items = $this->workflowPositionTypeService->listWorkflowPositionTypes();
            return datatables()->of($items)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "workflow-position-types"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow.position-types.index');
    }

    public function create()
    {
        $workflowPositionType = new WorkflowPositionType();
        $workflowPositionType->is_active = 1;
        if (request()->ajax()){
            return view('workflow.position-types.edit', compact("workflowPositionType"));
        }
        return view('workflow.position-types.create', compact('workflowPositionType'));
    }

    public function edit($id)
    {
        $workflowPositionType = $this->workflowPositionTypeService->findWorkflowPositionTypeById($id);

        if (request()->ajax()){
            return view('workflow.position-types.edit', compact('workflowPositionType'));
        }
        return redirect()->route('workflows.position-types.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        if (request()->ajax())
        {
            $workflowPositionType = $this->workflowPositionTypeService->findWorkflowPositionTypeById($id);

            if (request()->ajax()){
                return view('workflow.position-types.edit', compact('workflowPositionType'));
            }

            return redirect()->route('workflows.position-types.index');
        }
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
            'is_active' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['code'] = $data['position_code'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $workflowPositionType = $this->workflowPositionTypeService->findWorkflowPositionTypeById($request->input("id"));
            $results = $this->workflowPositionTypeService->updateWorkflowPositionType($data, $workflowPositionType);
        }else{
            $results = $this->workflowPositionTypeService->createWorkflowPositionType($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('workflows.position-types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $workflowPositionType = $this->workflowPositionTypeService->findWorkflowPositionTypeById($id);
        $result = $this->workflowPositionTypeService->deleteWorkflowPositionType($workflowPositionType);

        return $this->responseJson($result);
    }

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->workflowPositionTypeService->deleteMultiple($request->ids);
        return $this->responseJson($result);
    }
}
