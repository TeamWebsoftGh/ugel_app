<?php

namespace App\Http\Controllers\WorkflowRequest;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Workflow\WorkflowRequest;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Models\Workflow\WorkflowType;
use App\Utilities\WorkflowUtil;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EmployeeRequestController extends Controller
{
    use WorkflowUtil;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userId = user()->id;

        $query = WorkflowRequestDetail::query()
            ->where('workflow_request_details.implementor_id', $userId)
            ->where('workflow_request_details.status', 'PENDING')
            ->join('workflow_requests as b', 'workflow_request_details.workflow_request_id', '=', 'b.id')
            ->where('b.status', 'PENDING')
            ->selectRaw('workflow_request_details.*, b.workflow_requestable_type, b.workflow_requestable_id');

        // Filter by type if provided
        if ($request->has('type')) {
            $type = WorkflowType::firstWhere('code', $request->get('type'));
            if ($type) {
                $query->where('b.workflow_requestable_type', $type->subject_type);
            }
        }

        $workflowRequests = $query->latest();

        if ($request->ajax()) {
            return datatables()->of($workflowRequests)
                ->addIndexColumn()
                ->setRowId(fn($row) => $row->id)
                ->addColumn('client_name', fn($row) => optional($row->workflowRequest->client)->fullname)
                ->addColumn('employee_name', fn($row) => optional($row->employee)->fullname)
                ->addColumn('staff_id', fn($row) => optional($row->employee)->staff_id)
                ->addColumn('request_type', fn($row) => optional($row->workflowRequest->workflowType)->name)
                ->addColumn('request_date', fn($row) => optional($row->workflowRequest)->created_at)
                ->addColumn('action', function ($data) {
                    $route = route($data->approval_route, $data->workflow_requestable_id);
                    return '<a href="' . $route . '" class="show_new btn btn-info btn-sm"><i class="dripicons-preview"></i></a>';
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
    public function pending()
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
                    if (user()->can('edit-promotion'))
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRequests()
    {
        $workflowRequests = WorkflowRequest::where(['user_id' => user()->id])->orWhere(['created_by' => user()->id])->latest();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function allRequests(Request $request)
    {
        $workflowRequests = WorkflowRequestDetail::whereIn('status', ['pending']);
        $request_types = WorkflowType::all();

        $data = $request->all();
        if (!empty($data['filter_request_type']))
        {
            $workflowRequests = $workflowRequests->whereHas('workflowRequest', function ($query) use($data) {
                return $query->where('workflow_type_id', '=', $data['filter_request_type']);
            });
        }

        if ($request->has('search')&&!empty($request->search['value']))
        {
            $s = $request->search['value'];
                $workflowRequests = $workflowRequests->where(function ($query) use ($s) {
                    $query->whereHas('employee', function ($subquery) use ($s) {
                        $subquery->where('first_name', 'like', '%'.$s.'%')
                            ->orWhere('last_name', 'like', '%'.$s.'%');
                    })->orWhereHas('implementor', function ($subquery) use ($s) {
                        $subquery->where('first_name', 'like', '%'.$s.'%')
                            ->orWhere('last_name', 'like', '%'.$s.'%');
                    });
                });
        }


        if (request()->ajax())
        {
            return datatables()->of($workflowRequests->orderBy('updated_at', 'desc')->get())
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
                ->addColumn('request_detail', function ($row)
                {
                    $name = "<span>Request: ".($row->workflowRequest->workflowType->name ?? '')."</span>";
                    $stage = "<span>Stage : ".($row->workflowPositionType->name ?? '')."</span>";
                    return $name.'</br>'.$stage;
                })
                ->addColumn('employee_name', function ($row)
                {
                    $staff_id = "<span>Staff Id: ".($row->employee->staff_id ?? '')."</span>";
                    $employee = "<span>Name : ".($row->employee->fullname ?? '')."</span>";
                    $department  = "<span>Team : ".($row->employee?->team?->department_name ?? '')."</span>";

                    return $staff_id.'</br>'.$employee.'</br>'.$department;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    $implementor = User::find($row->implementor_id);
                    $employee = "<span>Name: ".(optional($implementor)->fullname??"N/A")."</span>";
                    $staff_id = "<span>Staff Id: ".(optional($implementor)->staff_id??"N/A")."</span>";

                    return $staff_id.'</br>'.$employee;
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowRequest->workflowType->name;
                })
                ->addColumn('request_date', function ($row)
                {
                    return $row->created_at;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" data-url="' . route('employee-requests.forward',$data->id) . '" class="show_new btn btn-info btn-sm forward_request"><i class="dripicons-forward"></i> Forward Request</button>';
                    return $button;
                })
                ->rawColumns(['action', 'employee_name', 'implementor_name', 'request_detail'])
                ->make(true);
        }

        return view('workflow-requests.employee-requests.all-requests', compact("request_types"));
    }

    public function forwardRequest(Request $request, $id)
    {
        $all_employees = Employee::all();
        $url = route('employee-requests.send-forward',$id);
        $view = view('workflow-requests.employee-requests.forward-request', compact('all_employees', 'url'))->render();
        return $view;
    }


    public function sendForwardRequest(Request $request, $id)
    {
        $new_implementor = $request->get('employee');
        if($wk =WorkflowRequestDetail::find($id)){
            $employee = Employee::find($new_implementor);
            $wk->update([
                'implementor_id'=> $new_implementor,
                'new_implementor_id'=> $wk->implementor_id,
            ]);
            $msq_subject='Request forwarded to you';
            $msq_body="Dear ".$employee->fullname." <br><br> A request has been forwarded to you for approval. <br> Kindly log in to your account and review the request.";

            $payload = [
                'to' => $employee->email,
                'subject' => $msq_subject,
                'message' => $msq_body,
                'is_sent' => 0,
                'requesteddate'=> date('Y-m-d h:i:s'),
                'datetosend'=> date('Y-m-d h:i:s'),
                'expiry_date' => date('Y-m-d h:i:s',strtotime('+12 months')),
                'module_name' => '',
                'module_id' => $wk->id,
                'receipient_id' => $employee->id,
                'requestor_id' => $wk->employee_id,
                'message_type' =>  'email',
                'read_status' => 'UNREAD',
                'sent_status' => 'PENDING',
                'request_id' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            DB::table('contract_alerts')->insert($payload);

            return [
                "MESSAGE" => "Request forwarded to new implementor",
                "STATUS_CODE" => 200,
                "RESPONSE_TYPE" => "SUCCESS",
            ];
        }else{
            return [
                "MESSAGE" => "Request not found",
                "STATUS_CODE" => 404,
                "RESPONSE_TYPE" => "ERROR",
            ];
        }
    }

    public function processRequest(Request $request)
    {
        $data = $request->all();
        $wk = WorkflowRequestDetail::find($data['workflow_request_detail']);

        if($wk == null)
            return redirect()->back();

        $results = $this->UpdateWorkflowRequest($wk, $data);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        return redirect()->back()->with('success', $results->message);
    }

    public function resendRequest(int $id)
    {
        $wk = WorkflowRequest::find($id);

        if($wk == null)
            return redirect()->back();

        $results = $this->ResendWorkflowRequest($wk);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->route("dashboard")->with('error', $results->message);
        }

        return redirect()->to("/")->with('success', $results->message);
    }
}
