<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\InvoiceResource;
use App\Services\Billing\Interfaces\IinvoiceService;
use App\Services\Helpers\PropertyHelper;
use Illuminate\Http\Request;

class InvoiceController extends MobileController
{
    /**
     * @var IInvoiceService
     */
    private IInvoiceService $invoiceService;

    public function __construct(IInvoiceService $invoiceService)
    {
        parent::__construct();
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_client'] = user()->client_id;
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $query = $this->invoiceService->listInvoices($data);

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = InvoiceResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = InvoiceResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

    public function create()
    {
        $data['hostels'] = PropertyHelper::getAllHostels();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }

    public function show($id)
    {
        $item = $this->invoiceService->findInvoiceById($id);
        $item = new InvoiceResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'booking_id' => 'required|exists:bookings,id',
          //  'property_id' => 'required|exists:properties,id',
            // 'client_id' => 'required|exists:clients,id',
         //   'lease_start_date' => 'required|date',
          //  'lease_end_date' => 'required|date|after_or_equal:lease_start_date',
        ]);

        $data = $request->except('_token', '_method', 'id', 'type');
        $item = $this->invoiceService->findInvoiceById($data['invoice_id']);

        $results = $this->invoiceService->updateInvoice($data, $item);

        if(isset($results->data))
        {
            $results->data = new InvoiceResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_period_id' => 'required|exists:invoice_periods,id',
            'property_id' => 'required|exists:properties,id',
            'property_unit_id' => 'required|exists:property_units,id',
            'room_id' => 'nullable|exists:rooms,id',
           // 'client_id' => 'required|exists:clients,id',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after_or_equal:lease_start_date',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['client_id'] = user()->client_id;
        $data['created_from'] = "api";
        $results = $this->invoiceService->createInvoice($data);

        if(isset($results->data))
        {
            $results->data = new InvoiceResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function destroy(int $id)
    {
        $invoice = $this->invoiceService->findInvoiceById($id);
        $results = $this->invoiceService->deleteInvoice($invoice);

        return $this->apiResponseJson($results);
    }
}
