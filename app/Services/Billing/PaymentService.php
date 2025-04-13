<?php

namespace App\Services\Billing;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Billing\Invoice;
use App\Models\Billing\Payment;
use App\Models\Payment\PaymentGateway;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Services\Billing\Interfaces\IPaymentService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;

class PaymentService extends ServiceBase implements IPaymentService
{
    private IPaymentRepository $paymentRepo;

    /**
     * PaymentService constructor.
     *
     * @param IPaymentRepository $paymentRepository
     */
    public function __construct(IPaymentRepository $paymentRepository)
    {
        parent::__construct();
        $this->paymentRepo = $paymentRepository;
    }

    /**
     * @return
     */
    public function listPayments(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->paymentRepo->listPayments($filters, $orderBy, $sortBy);
    }

    /**
     * @param array $data
     * @param Invoice $invoice
     * @return Response
     */
    public function createPayment(array $data, Invoice $invoice)
    {
        //Declaration
        $payment = null;
        try{
//            if($data['payment_method'] == "wallet")
//            {
//                if($data['amount'] > $invoice->client->wallet()->balance())
//                {
//                    return $this->errorResponse("You have insufficient funds to perform this transaction.");
//                }
//
//                $invoice->client->wallet()->pay($data['amount'], $invoice);
//                $invoice->total_paid += $data['amount'];
//
//                if($invoice->total_paid >= $invoice->total_amount)
//                {
//                    $data['status'] = 'paid';
//                }else{
//                    $data['status'] = 'partial';
//                }
//            }

            $payment_gateway = PaymentGateway::firstWhere('slug', $data['payment_method']);

            if($payment_gateway->mode == "offline")
            {
                $data['status'] = 'pending';
                $data['payment_gateway_id'] = $payment_gateway->id;
                $data['client_id'] = $invoice->client_id;
                $data['invoice_id'] = $invoice->id;
                if(isset($data['reference']))
                {
                    $data['transaction_id'] = $data['reference'];
                }else{
                    $data['transaction_id'] = generate_token();
                }
                $payment = $this->paymentRepo->create($data);
                return $this->buildCreateResponse($payment);
            }
            else{
                return $this->errorResponse("Payment option currently not available.");
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $payment, 'make-payment-failed');
            return $this->errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param array $data
     * @param Payment $payment
     * @return Response
     */
    public function updatePayment(array $data, Payment $payment)
    {
        $result = $this->paymentRepo->update($data, $payment->id);
        return $this->buildUpdateResponse($payment, $result);
    }


    /**
     * @param Order $order
     * @return Response
     */
    public function changeStatus(Payment $payment, $status)
    {
        $result = false;

        //Process Request
        try {
            if($payment->status == $status)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Payment status already ".$status;

                return $this->response;
            }

            if ($status == "confirm" && $payment->status == "pending")
            {
                $result = $this->paymentRepo->update(['status' => 'paid'], $payment->id);
                $payment->paymentable->wallet()->deposit($payment->amount, $payment);

                if(isset($payment->invoice))
                {
                   // return $this->confirmOrderPayment($payment->order, $payment->amount);
                }
            }elseif($status == "reject" && $payment->status == "pending")
            {
                $result = $this->paymentRepo->update(['status' => 'declined'], $payment->id);
            }else{
                $result = $this->paymentRepo->update(['status' => $status], $payment->id);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $payment, 'update-payment-status-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-payment-status-successful';
        $auditMessage = 'Payment status has successfully been updated to '.$status;

        log_activity($auditMessage, $payment, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param array $where
     * @return void
     */
    public function findPayment(array $where)
    {
        return $this->paymentRepo->findOneByOrFail($where);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePayment(int $id)
    {
        // TODO: Implement deletePayment() method.
    }

    public function listPaymentsByCustomer()
    {
        // TODO: Implement listPaymentsByCustomer() method.
    }
}
