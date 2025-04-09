<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Audit\LogActivity;
use App\Services\CustomerService\Interfaces\ISupportTicketService;
use App\Services\Helpers\PropertyHelper;
use App\Traits\TaskUtil;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupportTicketController extends Controller
{
    private ISupportTicketService $ticketService;

    Public function __construct(ISupportTicketService $ticketService)
    {
        parent::__construct();
        $this->ticketService = $ticketService;
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
        $data['filter_assignee'] = $request->get('filter_assignee', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_status'] = $request->get('filter_status', '');
        $data['filter_priority'] = $request->get('filter_priority', '');
        $data['filter_property'] = $request->get('filter_property', '');
        $data['categories'] = TaskUtil::getAllSupportTopics();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['statuses'] = TaskUtil::getAllStatuses();
        $data['properties'] = PropertyHelper::getAllProperties();

        if (request()->ajax())
        {
            $tickets = $this->ticketService->listSupportTickets($data);
            return datatables()->of($tickets)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('client_name', function ($row)
                {
                    return $row->client->fullname;
                })
                ->addColumn('client_number', function ($row)
                {
                    return $row->client->client_number;
                })
                ->addColumn('created_by', function ($row)
                {
                    return $row->owner->fullname;
                })
                ->addColumn('phone_number', function ($row)
                {
                    return $row->phone_number??"N/A";
                })
                ->addColumn('category', function ($row)
                {
                    return ucwords($row->supportTopic->name??"N/A");
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<a href="'.route("support-tickets.show", $data->id).'" title="show" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></a>';
                    $button .= '&nbsp;';
                    if (user()->can('update-support-tickets') && !str_contains($data->status, 'Closed') )
                    {
                        $button .= '<a href="'.route("support-tickets.edit", $data->id).'" title="Edit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-edit"></i></a>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-support-tickets') && !str_contains($data->status, 'Closed'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("customer-service.support-tickets.index", compact("data"));
    }

    public function myTickets(Request $request)
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
        $data['categories'] = TaskUtil::getAllSupportTopics();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['statuses'] = TaskUtil::getAllStatuses();
        $data['properties'] = PropertyHelper::getAllProperties();
        $data['user'] = user();
        return view("customer-service.support-tickets.index", compact("data"));
    }

    public function assigned(Request $request)
    {
        $user = user();
        $data = $request->all();

        $data['filter_assignee'] = $user->id;
        $data['filter_start_date'] = $request->get('filter_start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $data['filter_end_date'] = $request->get('filter_end_date', Carbon::now()->format('Y-m-d'));
        $data['filter_category'] = $request->get('filter_category', '');
        $data['filter_customer'] = $request->get('filter_customer', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_status'] = $request->get('filter_status', '');
        $data['filter_priority'] = $request->get('filter_priority', '');
        $data['filter_property'] = $request->get('filter_property', '');
        $data['categories'] = TaskUtil::getAllSupportTopics();
        $data['priorities'] = TaskUtil::getPriorities();
        $data['statuses'] = TaskUtil::getAllStatuses();
        $data['properties'] = PropertyHelper::getAllProperties();

        return view("customer-service.support-tickets.index", compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $data = $this->ticketService->getCreateTicket();
        if (request()->ajax()){
            return view('customer-service.support-tickets.edit', $data);
        }
        return view("customer-service.support-tickets.create", $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if(!$request->has('update_request')){
            $validatedData = $request->validate([
                'subject' => 'required',
                'ticket_note' => 'required',
                'priority_id' => 'required',
            ]);
        }else{
            $validatedData = $request->validate([
                'status' => 'required',
                'remarks' => 'required',
            ]);
        }

        if ($request->has("id") && $request->input("id") != null)
        {
            $task = $this->ticketService->findSupportTicketById($request->input("id"));
            $results = $this->ticketService->updateSupportTicket($data, $task);
        }else{
            $results = $this->ticketService->createSupportTicket($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('support-tickets.show', $results->data->id);
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
        $files = $ticket->attachments()->orderByDesc('created_at')->get();
        $comments = $ticket->ticketComments();
        $assigneeIds =  $ticket->assignees()->pluck("id")->toArray();
        return view("customer-service.support-tickets.show", compact("ticket", "logs", 'files', 'comments', 'assigneeIds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $ticket = $this->ticketService->findSupportTicketById($id);

        $data = $this->ticketService->getCreateTicket();
        $data['ticket'] = $ticket;
        return view("customer-service.support-tickets.create", $data);
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

    public function destroy(int $id)
    {
        $award = $this->ticketService->findSupportTicketById($id);
        $result = $this->ticketService->deleteSupportTicket($award);

        return $this->responseJson($result);
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

}
