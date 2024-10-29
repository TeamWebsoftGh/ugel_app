<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Traits\JsonResponseTrait;
use App\Models\Workflow\WorkflowPositionType;
use App\Services\Interfaces\IWorkflowPositionTypeService;
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
use function view;

class PositionTypeController extends Controller
{
    use JsonResponseTrait;

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
     * @return Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $workflowPositionType = new WorkflowPositionType();
        $workflowPositionTypes = $this->workflowPositionTypeService->listWorkflowPositionTypes();
        if (request()->ajax())
        {
            return datatables()->of($workflowPositionTypes)
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

        return view('workflow.position-types.create', compact( 'workflowPositionType'));
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
            'status' => 'required',
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
}
