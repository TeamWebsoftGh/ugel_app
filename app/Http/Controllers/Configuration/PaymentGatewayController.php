<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\Payment\PaymentGateway;
use App\Services\Interfaces\IPaymentGatewayService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentGatewayController extends Controller
{
    private IPaymentGatewayService $paymentGatewayService;

    /**
     * @param IPaymentGatewayService $paymentGateway
     */
    public function __construct(IPaymentGatewayService $paymentGateway)
    {
        parent::__construct();
        $this->paymentGatewayService = $paymentGateway;
    }

    public function index(Request $request)
    {
        $data = request()->all();
        if (request()->ajax())
        {
            $types = $this->paymentGatewayService->listAllPaymentGateways();

            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-payment-gateways'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-payment-gateways'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('configuration.payment.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $paymentGateway = new PaymentGateway();

        $data['paymentGateways'] = $this->paymentGatewayService->listAllPaymentGateways('updated_at', 'desc');
        $data['paymentGateway'] = $paymentGateway;

        if (request()->ajax()){
            return view('configuration.payment.edit', $data);
        }


        return view('configuration.payment.create', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function edit(Request $request, int $id)
    {
        $paymentGateway = $this->paymentGatewayService->findPaymentGatewayById($id);

        $data['paymentGateways'] = $this->paymentGatewayService->listAllPaymentGateways();
        $data['paymentGateway'] = $paymentGateway;

        if ($request->ajax()){

            if($paymentGateway->mode == "offline")
            {
                return view('configuration.payment.edit', $data);
            }
            else {
                $config = config('paymentgateways');
                $current_gateway = $paymentGateway->slug;
                $data['settings'] = $paymentGateway;
                $data['options'] = $config['generic_options'];
                $data['view_name'] = 'configuration.payment.'.$current_gateway;
                $data['current_gateway'] =$current_gateway;

                return view('configuration.payment.online', compact('data'));
            }
        }

        return redirect()->route("configuration.payment-gateways.create", ["serviceId" => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentGatewayRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(PaymentGatewayRequest $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $paymentGateway = $this->paymentGatewayService->findPaymentGatewayById($request->input("id"));
            if($paymentGateway->slug == 'paystack')
            {
                $data['settings'] = [
                    'public_key' => $request->public_key,
                    'secret_key' => $request->secret_key,
                    'base_url' => $request->base_url,
                    'merchant_email' => $request->merchant_email,
                ];
            }else if($paymentGateway->slug == 'stripe'){
                $data['settings'] = [
                    'publishable_key' => $request->publishable_key,
                    'secret_key' => $request->secret_key,
                    'base_url' => $request->base_url,
                ];
            }
            $results = $this->paymentGatewayService->updatePaymentGateway($data, $paymentGateway);
        }else{
            $results = $this->paymentGatewayService->createPaymentGateway($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);
        return redirect()->route("configuration.payments.create");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $paymentGateway = $this->paymentGatewayService->findPaymentGatewayById($id);
        $result = $this->paymentGatewayService->deletePaymentGateway($paymentGateway);

        return $this->responseJson($result);
    }
}
