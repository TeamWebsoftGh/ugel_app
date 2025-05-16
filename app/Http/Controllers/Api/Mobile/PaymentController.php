<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\PaymentOptionResource;
use App\Http\Resources\PaymentResource;
use App\Services\Billing\Interfaces\IPaymentService;
use App\Services\Billing\InvoiceService;
use App\Services\Interfaces\IPaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends MobileController
{
    private IPaymentService $paymentService;
    private InvoiceService $invoiceService;
    private IPaymentGatewayService $paymentGatewayService;

    public function __construct(IPaymentService $paymentService, InvoiceService $invoiceService, IPaymentGatewayService $paymentGatewayService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->invoiceService = $invoiceService;
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_client'] = user()->client_id;
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $query = $this->paymentService->listPayments($data);

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = PaymentResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = PaymentResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

    public function paymentOptions(Request $request)
    {
        $data['online'] = PaymentOptionResource::collection($this->paymentGatewayService->listOnlinePaymentGateways());
        $data['offline'] = PaymentOptionResource::collection($this->paymentGatewayService->listOfflinePaymentGateways());
        $data['show_wallet_option'] = false;

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|exists:payment_gateways,slug',
            'invoice_id' => 'required',
        ]);

        $data= $request->except('_token');
        $invoice = $this->invoiceService->findInvoiceById($data['invoice_id']);
        $data = $request->except('_token', '_method', 'id');
        $results = $request->filled('id')
            ? $this->paymentService->updatePayment($data, $this->paymentService->findPayment($request->input('id')))
            : $this->paymentService->createPayment($data, $invoice);

        return $this->apiResponseJson($results);
    }

    public function show($id)
    {
        $item = $this->paymentService->findPayment(['client_id' => user()->client_id, 'id' => $id]);
        $item = new PaymentResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function destroy(int $id)
    {
        $item = $this->paymentService->findPayment(['id' => $id, 'client_id' => user()->client_id]);
        $results = $this->paymentService->deletePayment($item);

        return $this->apiResponseJson($results);
    }
}
