<?php

namespace App\Services;

use App\Constants\ResponseType;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Services\Interfaces\IPayStackService;
use Unicodeveloper\Paystack\Facades\Paystack;

class PayStackService extends ServiceBase implements IPayStackService
{
    private $paymentRepo;

    /**
     * UserService constructor.
     *
     * @param IPaymentRepository $payment
     */
    public function __construct(IPaymentRepository $payment)
    {
        parent::__construct();
        $this->paymentRepo = $payment;
    }

    public function getLocalPaymentStatus($applicant)
    {

     return   $this->paymentRepo->paymentStatus($applicant);
    }

    public function getRemotePaymentStatus($applicant){

        return $this->payment->checkRemoteStatus($applicant);
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function CheckPaymentStatus(Order $order)
    {
        try {
            $paymentDetails = Paystack::getPaymentData();
            if($paymentDetails != null && $paymentDetails["status"] == true)
            {
                $amount = ($paymentDetails["data"]["amount"]);
                if($amount > 0)
                {
                    $amount = $amount/100;
                }
                if($paymentDetails["data"]["status"] == "success")
                {
                    $order->total_paid = $amount;
                    $order->save();
                }
                $data = [];
                $data["transaction_id"] = $paymentDetails["data"]["id"];
                $data["reference_id"] = $paymentDetails["data"]["reference"];
                $data["amount"] = $amount;
                $data["status"] = $paymentDetails["data"]["status"];
                $data["payment_method"] = $paymentDetails["data"]["channel"];
                $data["account_type"] = $paymentDetails["data"]["authorization"]["card_type"];
                $data["account_name"] = $paymentDetails["data"]["authorization"]["account_name"];
                $data["merchant_name"] = $paymentDetails["data"]["authorization"]["bank"];
                $payment = $this->paymentRepo->createPayment($data, $order);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), new Payment(), 'create-payment-failed');
        }

        //Audit Trail
        $logAction = 'create-payment-successful';
        $auditMessage = 'You have successfully added payment for: '.currencyFormat($payment->amount);

        log_activity($auditMessage, $payment, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payment;

        return $this->response;
    }

    /**
     * @param Order $order
     * @return Helpers\Response
     */
    public function makePayment(Order $order)
    {
        try{
            $payment =  Paystack::getAuthorizationUrl();
        }catch(\Exception $e) {
            log_error(format_exception($e), new Payment(), 'initiate-payment-failed');

            $this->response->status = ResponseType::ERROR;
            $this->response->message = 'The paystack token has expired. Please refresh the page and try again.';
            return $this->response;
        }

        //Audit Trail
        $logAction = 'initiate-payment-successful';
        $auditMessage = 'You have successfully initiated payment for: '.$order->total_cost;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payment;

        return $this->response;
    }
}
