<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\MaintenanceRequestRequest;
use App\Models\Audit\LogActivity;
use App\Services\Helpers\PropertyHelper;
use App\Services\Interfaces\IMaintenanceService;
use App\Traits\TaskUtil;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaintenanceRequestController extends Controller
{
    private IMaintenanceService $maintenanceService;

    Public function __construct(IMaintenanceService $maintenanceService)
    {
        parent::__construct();
        $this->maintenanceService = $maintenanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\Foundation\Application|View|JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_start_date'] = $request->get('filter_start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $data['filter_end_date'] = $request->get('filter_end_date', Carbon::now()->format('Y-m-d'));
        $data['filter_category'] = $request->get('filter_category', '');
        $data['filter_customer'] = $request->get('filter_customer', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_status'] = $request->get('filter_status', '');
        $data['filter_priority'] = $request->get('filter_priority', '');
        $data['filter_property'] = $request->get('filter_property', '');
        $data['categories'] = TaskUtil::getMaintenanceCategories();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['properties'] = PropertyHelper::getAllProperties();

        if (request()->ajax())
        {
            $items = $this->maintenanceService->listMaintenances($data);
            return datatables()->of($items)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('client_name', function ($row)
                {
                    return $row->client->fullname;
                })
                ->addColumn('client_phone', function ($row)
                {
                    return $row->client->phone_number;
                })
                ->addColumn('category_name', function ($row)
                {
                    return $row->maintenanceCategory->name??"N/A";
                })
                ->addColumn('property_name', function ($row)
                {
                    return $row->property->property_name??"N/A";
                })
                ->addColumn('priority_name', function ($row)
                {
                    return ucwords($row->priority->name??"N/A");
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-maintenance-requests'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-maintenance-requests'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("customer-service.maintenance-requests.index", compact("data"));
    }

    public function myTickets(Request $request)
    {
        $user = user();

        $data = $request->all();
        $data['filter_start_date'] = $request->get('filter_start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $data['filter_end_date'] = $request->get('filter_end_date', Carbon::now()->format('Y-m-d'));
        $data['filter_category'] = $request->get('filter_category', '');
        $data['filter_customer'] = $request->get('filter_customer', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_status'] = $request->get('filter_status', '');
        $data['filter_priority'] = $request->get('filter_priority', '');
        $data['filter_property'] = $request->get('filter_property', '');
        $data['categories'] = TaskUtil::getMaintenanceCategories();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['properties'] = PropertyHelper::getAllProperties();

        return view("customer-service.maintenance-requests.index", compact("data"));
    }

    public function assigned(Request $request)
    {
        $user = user();

        $data['filter_assignee'] = $user->id;
        $data['filter_start_date'] = $request->get('filter_start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $data['filter_end_date'] = $request->get('filter_end_date', Carbon::now()->format('Y-m-d'));
        $data['filter_category'] = $request->get('filter_category', '');
        $data['filter_customer'] = $request->get('filter_customer', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_status'] = $request->get('filter_status', '');
        $data['filter_priority'] = $request->get('filter_priority', '');
        $data['filter_property'] = $request->get('filter_property', '');
        $data['categories'] = TaskUtil::getMaintenanceCategories();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['properties'] = PropertyHelper::getAllProperties();

        return view("customer-service.maintenance-requests.index", compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $data = $this->maintenanceService->getCreateMaintenance();
        if (request()->ajax()){
            return view('customer-service.maintenance-requests.edit', $data);
        }
        return view("customer-service.maintenance-requests.create", $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(MaintenanceRequestRequest $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $task = $this->maintenanceService->findMaintenanceById($request->input("id"));
            $results = $this->maintenanceService->updateMaintenance($data, $task);
        }else{
            $results = $this->maintenanceService->createMaintenance($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('maintenance-requests.show', $results->data->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $ticket = $this->ticketService->findSupportTicketById($id);

        $logs = LogActivity::orderBy('id', 'desc')
            ->where([
                ['subject_type', 'App\Models\SupportTicket'],
                ['subject_id', $ticket->id],
            ])->get();
        $files = $ticket->documents()->orderByDesc('created_at')->get();
        $comments = $ticket->ticketComments();
        $assigneeIds =  $ticket->assignees()->pluck("id")->toArray();
        return view("customer-service.support-tickets.show", compact("ticket", "logs", 'files', 'comments', 'assigneeIds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|\Illuminate\Foundation\Application|object|View
     */
    public function edit($id)
    {
        $ticket = $this->maintenanceService->findMaintenanceById($id);

        $data = $this->maintenanceService->getCreateMaintenance();
        $data['maintenance'] = $ticket;
        if (request()->ajax()){
            return view('customer-service.maintenance-requests.edit', $data);
        }
        return view("customer-service.maintenance-requests.create", $data);
    }

    public function postComment(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
            'support_ticket_id' => 'required'
        ]);

        $task = $this->ticketService->findSupportTicketById($request->support_ticket_id);
        $data = $request->except('_token', '_method');
        $results = $this->ticketService->postComment($data, $task);

        if($request->ajax()){
            return $this->responseJson($results);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->back();
    }

    public function deleteComment($task_id, $id)
    {
        $task = $this->ticketService->findSupportTicketById($task_id);
        $comment = $task->ticketComments()->findOrFail($id);

        $result = $this->ticketService->deleteComment($comment, $task);

        return $this->responseJson($result);
    }

    public function uploadFile(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_files' => 'required',
            'ticket_id' => 'required'
        ]);

        $ticket = $this->ticketService->findSupportTicketById($request->ticket_id);
        $results = $this->ticketService->uploadDocument($request->except('_token', '_method'), $ticket);

        if($request->ajax()){
            return $this->responseJson($results);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->back();
    }

    public function deleteDocument($ticket_id, $id)
    {
        $ticket = $this->ticketService->findSupportTicketById($ticket_id);

        $document = $ticket->documents()->findOrFail($id);

        $result = $this->ticketService->deleteDocument($document, $ticket);
        return $this->responseJson($result);
    }

    public function downloadDocument($task_id, $id)
    {
        $task = $this->ticketService->findSupportTicketById($task_id);

        $document = $task->documents()->findOrFail($id);

        $result = $this->ticketService->deleteDocument($document, $task);
        return $this->responseJson($result);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $ticket = $this->ticketService->findSupportTicketById($id);

        $result = $this->ticketService->changeStatus($request->status,$ticket);

        return $this->responseJson($result);
    }


    public function destroy($id)
    {
        $task = $this->maintenanceService->findMaintenanceById($id);
        $result = $this->maintenanceService->deleteMaintenance($task);

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
        $result = $this->maintenanceService->deleteMultipleRequests($request->ids);
        return $this->responseJson($result);
    }
}
