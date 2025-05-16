<?php

namespace App\Services;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\WorkflowStatusChanged;
use App\Events\LeaveSubmittedEvent;
use App\Helpers\MaintenanceHelper;
use App\Helpers\StatusHelper;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IOffenseRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IOrderService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderService extends ServiceBase implements IOrderService
{
    private $orderRepo;
    private $customerRepo;
    private $serviceTypeRepo;

    /**
     * OrderService constructor.
     *
     * @param IOrderRepository $orderRepository
     * @param ICustomerRepository $customerRepository
     * @param IOffenseRepository $serviceTypeRepo
     */
    public function __construct(
        IOrderRepository $orderRepository,
        ICustomerRepository $customerRepository,
        IOffenseRepository $serviceTypeRepo
    ){
        $this->orderRepo = $orderRepository;
        $this->customerRepo = $customerRepository;
        $this->serviceTypeRepo = $serviceTypeRepo;
        parent::__construct();
    }

    /**
     * List all the Orders
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listOrders(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->orderRepo->listOrders($order, $sort);
    }

    /**
     * Create the Orders
     *
     * @param array $params
     * @return Response
     */
    public function createOrder(array $params)
    {
        //Declaration
        $order = null;

        //Process Request
        try {
            $params["order_no"] = generate_order_number();
            $params["status_id"] = MaintenanceHelper::INCOMPLETE;
            $order = $this->orderRepo->createOrder($params);

            if (isset($params['customer_files'])) {
                $files = collect($params['customer_files']);
                $this->orderRepo->saveImages($files, $order, "customer");
            }

            if (isset($params['order_files'])) {
                $files = collect($params['order_files']);
                $this->orderRepo->saveImages($files, $order, "writer");
            }

        } catch (\Exception $e) {
            log_error(format_exception($e), new Order(), 'create-order-failed');
        }

        //Check if Order was created successfully
        if (!$order || $order == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-order-successful';
        $auditMessage = 'You have successfully added a new Order for '.$order->service->name;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $order;

        return $this->response;
    }

    /**
     * Find the Order by id
     *
     * @param int $id
     *
     * @return Order
     */
    public function findOrderById(int $id): Order
    {
        return $this->orderRepo->findOrderById($id);
    }

    /**
     * Update Order
     *
     * @param array $params
     * @param Order $order
     * @return Response
     */
    public function updateOrder(array $params, Order $order)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            if (isset($params['customer_files'])) {
                $files = collect($params['customer_files']);
                $this->orderRepo->saveImages($files, $order, "customer");
            }

            if (isset($params['order_files'])) {
                $files = collect($params['order_files']);
                $this->orderRepo->saveImages($files, $order, "writer");
            }

            $result = $this->orderRepo->updateOrder($params, $order);

            if(isset($params['writer_id']) && ($order->writer_id == null || $order->writer_id == '')){
                send_mail(OrderAssignedMail::class, $order, $order->writer);
            }

        } catch (\Exception $e) {
            log_error(format_exception($e), $order, 'update-order-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-order-successful';
        $auditMessage = 'Order successfully updated for '. $order->service->name;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $order;

        return $this->response;
    }

    /**
     * Update Order
     *
     * @param $status
     * @param Order $order
     * @return Response
     */
    public function changeStatus($status, Order $order)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $status = StatusHelper::getById($status);
            $oldStatus =  $order->orderStatus->name;

            if($status->id == MaintenanceHelper::INCOMPLETE){
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot update this order status. Complete the order and try again.";

                return $this->response;
            }

            if ($status->name == $oldStatus)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Order already ".$status->name;

                return $this->response;
            }

            $result = $this->orderRepo->updateOrder(['status_id' => $status->id], $order);
            event(new WorkflowStatusChanged($order));

        } catch (\Exception $e) {
            log_error(format_exception($e), $order, 'update-Order-status-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-order-status-successful';
        $auditMessage = 'Order has successfully been '.$status->name;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Order $order
     * @return Response
     */
    public function deleteOrder(Order $order)
    {
        //Declaration
        $result = false;

        try{
            if ($order->status_id != MaintenanceHelper::INCOMPLETE)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Order.";

                return $this->response;
            }

            $order->customerDocuments()->delete();
            $order->orderDocuments()->delete();
            $result = $this->orderRepo->deleteOrder($order);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $order, 'delete-order-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-order-successful';
        $auditMessage = 'You have successfully deleted Order for '.$order->service->name;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Order $order
     * @param int $status
     * @return Response
     */
    public function submitOrder(Order $order, $status = MaintenanceHelper::SUBMITTED)
    {
        //Declaration
        $result = false;

        if($order->status_id != MaintenanceHelper::INCOMPLETE)
        {
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = "Order successfully updated for ".$order->service->name;

            return $this->response;
        }

        //Process Request
        try {
            $result = $this->orderRepo->updateOrder([
                'date_submitted' => Carbon::now(),
                'status' => 1,
                'status_id' => MaintenanceHelper::SUBMITTED
            ], $order);

            event(new LeaveSubmittedEvent($order));
        } catch (\Exception $e) {
            log_error(format_exception($e), $order, 'submit-order-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'submit-order-successful';
        $auditMessage = 'You have successfully submitted an Order for : '.$order->service->name;

        log_activity($auditMessage, $order, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function getCreateOrder(){
        $order = new Order();
        $order->pages = 4;
        return [
            'academic_levels' => AcademicLevel::getSelectData(),
            'momo_types' => Constants::MOMO_TYPES,
            'durations' => Duration::getSelectData(),
            'services' => $this->serviceTypeRepo->listServiceTypes()->where("status", "==", 1),
            'disciplines' => PaperType::all()->where("status", '==', 1),
            'order' => $order,
            'price' => new Price(),
            'word_limit' => Constants::WORDS_PER_PAGE,
        ] ;
    }
}
