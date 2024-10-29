<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Models\Common\Priority;
use App\Services\Interfaces\IClientService;
use App\Services\Interfaces\ISupportTicketService;
use Illuminate\Http\Request;

class SupportTicketController extends MobileController
{
    public ISupportTicketService $ticketService;
    public IClientService $clientService;

    public function __construct(ISupportTicketService $ticketService, IClientService $clientService)
    {
        parent::__construct();
        $this->ticketService = $ticketService;
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_client'] = user()->id;
        $tickets = $this->ticketService->listSupportTickets($data);

        return $this->sendResponse("000", "",  SupportTicketResource::collection($tickets));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');
        $names = Priority::pluck('name')->implode(', ');
        $validatedData = $request->validate([
            'subject' => 'required',
            'ticket_note' => 'required',
            'priority_name' => 'required|exists:priorities,name',  // Ensure the priority exists in the database
            'customer_phone_number' => 'nullable',
            'customer_email' => 'nullable',
            'customer_name' => 'nullable',
        ], [
            'priority_name.exists' => "The :attribute must be {$names}.",  // Custom error message
        ]);

        $priority = Priority::firstWhere('name', $validatedData['priority_name']);

        $data['priority_id'] = $priority?->id??1;
        $data['client_id'] = user()->id;
        $data['created_from'] = "mobile-app";

        $result = $this->ticketService->createSupportTicket($data);

        return $this->apiResponseJson($result);
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');
        $names = Priority::pluck('name')->implode(', ');

        $validatedData = $request->validate([
            'ticket_id' => 'required',
            'subject' => 'required',
            'ticket_note' => 'required',
            'priority_name' => 'required|exists:priorities,name',  // Ensure the priority exists in the database
            'customer_phone_number' => 'nullable',
            'customer_email' => 'nullable',
            'customer_name' => 'nullable',
        ], [
            'priority_name.exists' => "The :attribute must in {$names}.",  // Custom error message
        ]);

        $priority = Priority::firstWhere('name', $validatedData['priority_name']);
        $data['priority_id'] = $priority?->id??1;
        $ticket = $this->ticketService->findSupportTicketById($data['ticket_id']);
        $result = $this->ticketService->updateSupportTicket($data, $ticket);

        return $this->apiResponseJson($result);
    }

    public function show(int $id)
    {
        $ticket = $this->ticketService->findSupportTicketById($id);

        if($ticket->client_id != user()->id)
        {
            abort(404);
        }

        return $this->sendResponse("000", "", new SupportTicketResource($ticket));
    }
}
