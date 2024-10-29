<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IPaymentRepository;
use App\Services\Interfaces\IPaymentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PaymentService extends ServiceBase implements IPaymentService
{

    private $orderRepo;
    private $paymentRepo;

    /**
     * PaymentService constructor.
     *
     * @param IOrderRepository $order
     * @param IPaymentRepository $paymentRepository
     */
    public function __construct(IPaymentRepository $paymentRepository)
    {
        parent::__construct();
        $this->paymentRepo = $paymentRepository;
    }

    /**
     * @return void
     */
    public function listPayments()
    {
        return $this->paymentRepo->listPayments();
    }

    /**
     * @param array $data
     *
     * @param Order $order
     * @return Helpers\Response
     */
    public function createPayment(array $data, Order $order)
    {
        //Declaration
        $payment = null;

        //Process Request
        try {
            $payment = $this->paymentRepo->createPayment($data, $order);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Payment(), 'create-payment-failed');
        }

        //Check if Payment was created successfully
        if (!isset($payment->order_id) || $payment->order_id == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-payment-successful';
        $auditMessage = 'You have successfully added payment for: '.$payment->amount;

        log_activity($auditMessage, $payment, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payment;

        return $this->response;
    }

    /**
     * @param array $params
     * @param Payment $payment
     * @return void
     */
    public function updatePayment(array $params, Payment $payment)
    {
        // TODO: Implement updatePayment() method.
    }

    /**
     * @param int $id
     *
     * @return Order
     */
    public function findPaymentById(int $id)
    {
        // TODO: Implement findPaymentById() method.
    }

    /**
     * @param Order $order
     * @return array
     */
    public function changePaymentStatus(Order $order)
    {
        if ($order->hasPaidFees){
            $order->amountPaid = 0.00;
            $order->save();
        }else{
            $order->amountPaid = $order->applicationFees;
            $order->save();
        }

        //Audit Trail
        $logAction = $order->hasPaidFees?'confirm':'revert'.'-payment-successful';
        $auditMessage = $order->hasPaidFees?'Confirm':'Revert'.' Payment Changed Successfully for '.$order->applicationNumber;

        log_activity($auditMessage, $order, $logAction);

        return ['status' => 'Success', 'message' => $auditMessage, 'Order' => $order];
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
