<?php

namespace App\Http\Controllers\WorkflowRequest;

use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowRequest;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Models\Workflow\WorkflowType;
use App\Services\Workflow\Interfaces\IWorkflowRequestService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WorkflowRequestController extends Controller
{
    /**
     * @var IWorkflowRequestService
     */
    private IWorkflowRequestService $workflowRequestService;

    /**
     * ContactController constructor.
     *
     * @param IWorkflowRequestService $workflowRequestService
     */
    public function __construct(IWorkflowRequestService $workflowRequestService)
    {
        parent::__construct();
        $this->workflowRequestService = $workflowRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $workflowRequests = $this->workflowRequestService->listWorkflowRequests($data);
            return datatables()->of($workflowRequests)
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('stage', function ($row)
                {
                    return $row->workflow?->workflowPositionType->name??"N/A";
                })
                ->addColumn('requester_name', function ($row)
                {
                    return $row->client->fullname??$row->user->fullname;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    return optional($row->currentImplementor())->fullname??"N/A";
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowType->name;
                })
                ->addColumn('action', function ($data)
                {
                    $route = route($data->workflowType->approval_route, $data->workflow_requestable_id);
                    $button = '<a href="' . $route . '" class="show_new btn btn-info btn-sm"><i class="las la-eye"></i></a>';
                    if($data->status == "pending")
                    {
                        $button .= '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-primary btn-sm"><i class="dripicons-forward"></i> Forward Request</button>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'request_detail'])
                ->make(true);
        }

        return view('workflow-requests.index');
    }


    public function pending(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $data['filter_implementor'] = user_id();
            $workflowRequests = $this->workflowRequestService->listWorkflowRequestDetails($data);
            return datatables()->of($workflowRequests)
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('stage', function ($row)
                {
                    return $row->workflow?->workflowPositionType->name??"N/A";
                })
                ->addColumn('requester_name', function ($row)
                {
                    return $row->workflowRequest->client->fullname??$row->workflowRequest->user->fullname;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    return optional($row->implementor)->fullname??"N/A";
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowRequest->workflowType->name;
                })
                ->addColumn('action', function ($data)
                {
                    $route = route($data->workflowRequest->workflowType->approval_route, $data->workflowRequest->workflow_requestable_id);
                    return '<a href="' . $route . '" class="show_new btn btn-info btn-sm"><i class="las la-eye"></i></a>';
                })
                ->rawColumns(['action', 'request_detail'])
                ->make(true);
        }

        return view('workflow-requests.pending');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRequests(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $workflowRequests = $this->workflowRequestService->listWorkflowRequests($data);
            return datatables()->of($workflowRequests)
                ->addIndexColumn()
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('stage', function ($row)
                {
                    return $row->workflow?->workflowPositionType->name??"N/A";
                })
                ->addColumn('requester_name', function ($row)
                {
                    return $row->client->fullname??$row->user->fullname;
                })
                ->addColumn('implementor_name', function ($row)
                {
                    return optional($row->currentImplementor())->fullname??"N/A";
                })
                ->addColumn('request_type', function ($row)
                {
                    return $row->workflowType->name;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" id="' . $data->id . '" class="show_new btn btn-info btn-sm"><i class="dripicons-forward"></i> Forward Request</button>';
                    return $button;

                })
                ->rawColumns(['action', 'request_detail'])
                ->make(true);
        }

        return view('workflow-requests.my-requests');
    }
}
