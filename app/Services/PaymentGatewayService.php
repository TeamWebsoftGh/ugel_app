<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Payment\PaymentGateway;
use App\Repositories\Interfaces\IPaymentGatewayRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPaymentGatewayService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PaymentGatewayService extends ServiceBase implements IPaymentGatewayService
{
    private IPaymentGatewayRepository $paymentGatewayRepo;

    /**
     * PaymentGatewayService constructor.
     *
     * @param IPaymentGatewayRepository $paymentGatewayRepository
     */
    public function __construct(IPaymentGatewayRepository $paymentGatewayRepository)
    {
        parent::__construct();
        $this->paymentGatewayRepo = $paymentGatewayRepository;
    }

    /**
     * List all the PaymentGateways
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listAllPaymentGateways(string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->paymentGatewayRepo->listPaymentGateways($order, $sort);
    }

    /**
     * List Online the PaymentGateways
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listOnlinePaymentGateways(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->paymentGatewayRepo->listPaymentGateways($order, $sort)
            ->where('mode', '==', 'online')
            ->where('is_active', '==', 1);
    }

    /**
     * List Online the PaymentGateways
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listOfflinePaymentGateways(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->paymentGatewayRepo->listPaymentGateways($order, $sort)
            ->where('mode', '==', 'offline')
            ->where('is_active', '==', 1);
    }

    /**
     * Create the PaymentGateways
     *
     * @param array $params
     * @return Response
     */
    public function createPaymentGateway(array $params)
    {
        //Declaration
        $paymentGateway = null;

        //Process Request
        try {
            if($params['mode'] == 'offline')
            {
                $params['settings'] = [
                    'requires_transaction_number' => $params['requires_transaction_number']??0,
                    'requires_uploading_attachment' => $params['requires_uploading_attachment']??0,
                    'reference_field_label' => $params['reference_field_label'],
                    'attachment_field_label' => $params['attachment_field_label'],
                ];
            }

            if($params['mode'] == 'online')
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Please contact developer to set-up an online platform.";

                return $this->response;
            }
            $params['slug'] = Str::slug($params['name']);
            $paymentGateway = $this->paymentGatewayRepo->createPaymentGateway($params);

        } catch (\Exception $e) {
            log_error(format_exception($e), new PaymentGateway(), 'create-payment-gateway-failed');
        }

        //Check if PaymentGateway was created successfully
        if (!$paymentGateway)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-payment-gateway-successful';
        $auditMessage = 'You have successfully added a new Payment Gateway: '.$paymentGateway->name;

        log_activity($auditMessage, $paymentGateway, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $paymentGateway;

        return $this->response;
    }

    /**
     * Find the PaymentGateway by id
     *
     * @param int $id
     *
     * @return PaymentGateway
     */
    public function findPaymentGatewayById(int $id): PaymentGateway
    {
        return $this->paymentGatewayRepo->findPaymentGatewayById($id);
    }

    /**
     * Find the PaymentGateway by Slug
     *
     * @param string $slug
     * @return PaymentGateway
     */
    public function findPaymentGatewayBySlug(string $slug): PaymentGateway
    {
        return $this->paymentGatewayRepo->findOneByOrFail(['slug' => $slug]);
    }

    /**
     * Update PaymentGateway
     *
     * @param array $params
     * @param PaymentGateway $paymentGateway
     * @return Response
     */
    public function updatePaymentGateway(array $params, PaymentGateway $paymentGateway)
    {
        //Declaration
        $result = false;

        try{
            if($paymentGateway->mode == 'offline')
            {
                $params['settings'] = [
                    'requires_transaction_number' => $params['requires_transaction_number']??0,
                    'requires_uploading_attachment' => $params['requires_uploading_attachment']??0,
                    'reference_field_label' => $params['reference_field_label'],
                    'attachment_field_label' => $params['attachment_field_label'],
                ];
            }

            $result = $this->paymentGatewayRepo->updatePaymentGateway($params, $paymentGateway);

            if($paymentGateway->slug == 'paystack')
            {
                updateEnvKeys([
                    'PAYSTACK_PUBLIC_KEY' => $params['settings']['public_key'],
                    'PAYSTACK_SECRET_KEY' => $params['settings']['secret_key'],
                    'PAYSTACK_PAYMENT_URL' => $params['settings']['base_url'],
                    'MERCHANT_EMAIL' => $params['settings']['merchant_email'],
                ]);
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $paymentGateway, 'update-payment-gateway-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-payment-gateway-successful';
        $auditMessage = 'You have successfully updated a PaymentGateway with name: '.$paymentGateway->name;

        log_activity($auditMessage, $paymentGateway, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param PaymentGateway $paymentGateway
     * @return Response
     */
    public function deletePaymentGateway(PaymentGateway $paymentGateway)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->paymentGatewayRepo->deletePaymentGateway($paymentGateway);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $paymentGateway, 'delete-payment-gateway-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-payment-gateway-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE. " PaymentGateway: " . $paymentGateway->name;

        log_activity($auditMessage, $paymentGateway, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
