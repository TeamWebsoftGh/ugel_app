<?php

namespace App\Http\Controllers\Payment;

use App\Abstracts\Http\Controller;
use App\Services\Interfaces\IPaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private IPaymentService $paymentService;

    public function __construct(IPaymentService $IPaymentService)
    {
        parent::__construct();
        $this->paymentService = $IPaymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|void
     */
    public function index()
    {
        $payments = $this->paymentService->listPayments()->where('status', '==', 'success');
        return view('admin.sales.payments.index', compact("payments"));
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
