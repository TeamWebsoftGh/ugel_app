<?php

namespace App\Http\Controllers\WorkflowRequest;

use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Models\Workflow\WorkflowType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WorkflowRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
       $workflowRequests = WorkflowRequestDetail::where(['workflow_request_details.implementor_id' => user()->id])
       ->join('workflow_requests as b','workflow_request_details.workflow_request_id','b.id')
       ->whereIn('workflow_request_details.status', ['PENDING'])
       ->whereIn('b.status', ['PENDING'])
       ->selectRaw('workflow_request_details.*,b.workflow_requestable_type')
       ->latest();

        if($request->has("type"))
        {
            $type = WorkflowType::firstWhere('code', $request->get('type'));
            $workflowRequests = $workflowRequests->where('b.workflow_requestable_type',optional($type)->subject_type);
        }

        if (request()->ajax())
        {
            return datatables()->of($workflowRequests)
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#wf_requests-content';
                    },
                ])
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->fullname;
                })
                ->addColumn('department', function ($row)
                {
                    return $row->employee->department->department_name;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowRequest->workflowType->name;
                })
                ->addColumn('request_date', function ($row)
                {
                    return $row->workflowRequest->created_at;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<a href="'.route($data->approval_route).'?id='.$data->id.'" name="show" id="' . $data->id . '" class="show_new btn btn-info btn-sm"><i class="dripicons-preview"></i></a>';
                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow-requests.employee-requests.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function store(Request $request)
    {
        $workflowRequests = WorkflowRequestDetail::where(['implementor_id' => user()->id])->latest();
//        $workflowRequests = WorkflowRequestDetail::latest();
        if (request()->ajax())
        {
            return datatables()->of($workflowRequests)
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#wf_requests-content';
                    },
                ])
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->fullname;
                })
                ->addColumn('department', function ($row)
                {
                    return $row->employee->department->department_name;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowRequest->workflowType->name;
                })
                ->addColumn('request_date', function ($row)
                {
                    return $row->workflowRequest->created_at;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-success btn-sm"><i class="dripicons-preview"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    if (auth()->user()->can('edit-promotion'))
                    {
                        $button .= '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow-requests.employee-requests.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function myRequests()
    {
        $workflowRequests = WorkflowRequest::where(['employee_id' => user()->id])->latest();
        if (request()->ajax())
        {
            return datatables()->of($workflowRequests)
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#wf_requests-content';
                    },
                ])
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->fullname;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    return optional($row->currentImplementor())->fullname??"N/A";
                })
                ->addColumn('department', function ($row)
                {
                    return optional($row->currentImplementor())->department->department_name??"N/A";
                })
                ->addColumn('staff_id', function ($row)
                {
                    return optional($row->currentImplementor())->staff_id??"N/A";
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowType->name;
                })
                ->addColumn('request_date', function ($row)
                {
                    return $row->created_at;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-success btn-sm"><i class="dripicons-preview"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow-requests.employee-requests.my-requests');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function allRequests()
    {
        $workflowRequests = WorkflowRequest::whereIn('status', ['pending'])->latest();
        if (request()->ajax())
        {
            return datatables()->of($workflowRequests)
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#wf_requests-content';
                    },
                ])
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->fullname;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    return optional($row->currentImplementor())->fullname??"N/A";
                })
                ->addColumn('department', function ($row)
                {
                    return optional($row->currentImplementor())->department->department_name??"N/A";
                })
                ->addColumn('staff_id', function ($row)
                {
                    return optional($row->currentImplementor())->staff_id??"N/A";
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowType->name;
                })
                ->addColumn('request_date', function ($row)
                {
                    return $row->created_at;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-info btn-sm"><i class="dripicons-forward"></i> Forward Request</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('workflow-requests.employee-requests.all-requests');
    }
}
