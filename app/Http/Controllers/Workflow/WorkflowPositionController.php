<?php

namespace App\Http\Controllers\Workflow;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Services\Interfaces\IWorkflowPositionService;
use App\Traits\WorkflowUtil;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class WorkflowPositionController extends Controller
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
        if (request()->ajax())
        {
            $workflowPositions = $this->workflowPositionService->listWorkflowPositions('updated_at');
            return datatables()->of($workflowPositions)
                ->setRowId(function ($award)
                {
                    return $award->id;
                })
                ->addIndexColumn()
                ->addColumn('workflow_position_type_name', function ($row)
                {
                    return $row->workflowPositionType->name ?? '';
                })
                ->addColumn('category', function ($row)
                {
                    return $row->subject->name ?? 'N/A';
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->user->fullname ?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-workflow-positions'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-workflow-positions'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow.positions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function create()
    {
        $positionTypes = WorkflowPositionType::where('is_active', 1)->get();
        $workflowPosition = new WorkflowPosition();

        if (request()->ajax()){
            return view('workflow.positions.edit', compact('positionTypes', 'workflowPosition'));
        }

        return redirect()->route('workflows.positions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function edit($id)
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
            'user_id' => 'required',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

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
