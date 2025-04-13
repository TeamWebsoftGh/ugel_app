<?php

namespace App\Http\Controllers\Billing;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Services\Billing\Interfaces\IPaymentService;
use App\Services\Billing\InvoiceService;
use App\Services\Interfaces\IPaymentGatewayService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $amenities = $this->paymentService->listPayments($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('client_name', fn($row) => $row->client->fullname)
                ->addColumn('client_number', fn($row) => $row->client->client_number)
                ->addColumn('invoice_number', fn($row) => $row->invoice->invoice_number)
                ->addColumn('payment_gateway_name', fn($row) => $row->paymentGateway->name)
                ->addColumn('formatted_total', fn($row) => format_money($row->total_amount))
                ->addColumn('action', function ($data)
                {
                    $button = '<a href="' . route("invoices.show", $data->id) . '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></a>';
                    $button .= '&nbsp;';
                    if (user()->can('update-paymentss'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-payments'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('billing.payments.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|void
     */
    public function pendingPayments()
    {
        $payments = $this->paymentService->listPayments()->where('status', '==', 'pending');
        //$payments = Payment::all();

        return view('admin.sales.payments.index', compact("payments"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|void
     */
    public function unsuccessfulPayments()
    {
        $payments = $this->paymentService->listPayments()->where('status', '!=', 'pending')
            ->where('status', '!=', 'success');
        //$payments = Payment::all();

        return view('admin.payments.index', compact("payments"));
    }

    public function create(Request $request)
    {
        $invoice_id = $request->invoice_id;
        if(isset($invoice_id))
        {
            $invoice = $this->invoiceService->findInvoiceById($invoice_id);
            if(is_null($invoice)){
                return redirect()->back()->with('error', 'Sorry the request was not successful, please try again');
            }
        }

        $data = [];

        $data['amount'] = $invoice->total_price;
        $data['invoice'] = $invoice;
        $data['reason'] = "Invoice";
        $data["payment_options"] = [
            'online' => $this->paymentGatewayService->listOnlinePaymentGateways(),
            'offline' => $this->paymentGatewayService->listOfflinePaymentGateways(),
            'show_wallet_option' => false
        ];

        return view('billing.payments.create', $data);
    }

    public function showPay(string $slug, Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = $this->invoiceService->findInvoiceById($invoice_id);
        $data = [];

        if(isset($invoice))
        {
            $data['invoice_id'] = $invoice->id;
            $data['amount'] = $invoice->balance;
            $data['minPayment'] = $invoice->minPayment;
        }else{
            $data['amount'] = $request->amount;
            $data['minPayment'] = settings('minimum_top_up', 50.00);
            $data['invoice_id'] = null;
        }

        if($slug == "wallet" && isset($invoice))
        {
            if($data['amount'] > user()->wallet()->balance())
            {
                return redirect()->back()->with('error', 'Your wallet doesn\'t have sufficient balance');
            }
            return view('customer.payment.wallet', compact('invoice'));
        }else {
            $paymentMethod = $this->paymentGatewayService->listAllPaymentGateways()->firstWhere('slug', '==', $slug);
            $data['paymentMethod'] = $paymentMethod;

            if($paymentMethod->mode == 'offline')
            {
                return view('billing.payments.offline', compact('data', 'invoice'));
            }

            if($paymentMethod->slug == 'paystack')
            {
                return view('billing.payments.paystack', compact('data', 'invoice'));
            }
        }

        return redirect()->back()->with('error', 'Sorry the request was not successful, please try again');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required',
        ]);

        $data= $request->except('_token');
        $invoice = $this->invoiceService->findInvoiceById($data['invoice_id']);
        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->paymentService->updatePayment($data, $this->paymentService->findPayment($request->input('id')))
            : $this->paymentService->createPayment($data, $invoice);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'payments.index');
    }

    /**
     * @param string $id
     * @return Application|Factory|View
     */
    public function Show(string $id)
    {
        $payment = $this->paymentService->findPayment(["transaction_id" => $id]);
        $customer = $payment->customer;
        $order = $payment->order;

        return view('admin.payments.show', compact('order', 'payment', 'customer'));
    }

    public function changeStatus(int $id, Request $request)
    {
        $payment = $this->paymentService->findPaymentById($id);

        $result = $this->paymentService->changeStatus($payment, $request->status);

        return $this->responseJson($result);
    }
}
