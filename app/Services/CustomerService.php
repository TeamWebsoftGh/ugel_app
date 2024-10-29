<?php

namespace App\Services;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\WorkflowRequestEvent;
use App\Mail\Customer\ChangePasswordMail;
use App\Models\Customer;
use App\Models\Traits\UploadableTrait;
use App\Repositories\Interfaces\ICustomerRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ICustomerService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerService extends ServiceBase implements ICustomerService
{
    use UploadableTrait;

    private $customerRepo;

    /**
     * CustomerService constructor.
     *
     * @param ICustomerRepository $customerRepository
     */
    public function __construct(ICustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->customerRepo = $customerRepository;
    }

    /**
     * List all the Customers
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listCustomers(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->customerRepo->listCustomers($order, $sort);
    }

    /**
     * Create the Customers
     *
     * @param array $params
     * @return Response
     */
    public function createCustomer(array $params)
    {
        //Declaration
        $customer = null;
        $password = Str::random(10);

        //Process Request
        try {
            if (isset($data['username'])){
                $params['username'] = Str::slug($params['username']);
            }else{
                $params['username'] = generate_username('user');
            }
            if (!isset($data['password'])){
                $params['password'] = $password;
            }

//            if (isset($params["referral_code"])){
//                $customer = $this->customerRepo->findOneBy(["referral_code"=> $params["referral_code"]]);
//
//                if(isset($customer) && $customer != null){
//                    $customer->balance += Constants::BONUS_AMOUNT;
//                }
//            }

            $params["balance"] = Constants::BONUS_AMOUNT;
            $customer = $this->customerRepo->createCustomer($params);
            event(new WorkflowRequestEvent($customer));
        } catch (\Exception $e) {
            log_error(format_exception($e), new Customer(), 'create-customer-failed');
        }

        //Check if Customer was created successfully
        if (!$customer || $customer == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-customer-successful';
        $auditMessage = 'You have successfully added a new Customer: '.$customer->fullname.' with password: '.$password;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $customer;

        return $this->response;
    }


    /**
     * Find the Customer by id
     *
     * @param int $id
     *
     * @return Customer
     */
    public function findCustomerById(int $id): Customer
    {
        return $this->customerRepo->findCustomerById($id);
    }

    /**
     * Update Customer
     *
     * @param array $params
     * @param Customer $customer
     * @param string $type
     * @return Response
     */
    public function updateCustomer(array $params, Customer $customer, string $type="")
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->customerRepo->updateCustomer($params, $customer);
        } catch (\Exception $e) {
            log_error(format_exception($e), $customer, 'update-customer-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-customer-successful';
        $auditMessage = 'You have successfully updated a Customer: '.$customer->fullname;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $customer;

        return $this->response;
    }

    /**
     * @param Customer $customer
     * @return Response
     */
    public function resetPassword(Customer $customer)
    {
        //Declaration
        $res = false;
        $password = Str::random(10);

        //Process Request
        try {
            $res = $this->customerRepo->updateCustomer([
                'password' => $password,
                'ask_password_reset' => 1,
                'last_password_reset' => Carbon::now()
            ], $customer);

            if (Constants::SEND_PASSWORD_RESET_MAIL){
                $customer->password = $password;
                send_mail(ChangePasswordMail::class, $customer, $customer);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $customer, "reset-customer-password-failed");
        }

        //Check if User was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "reset-user-password-successful";
        $auditMessage = "Successfully changed password for " . $customer->fullname;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = "You have successfully reset this customer's password to <b>" . $password . "</b>";

        return $this->response;
    }

    /**
     * Update Customer
     *
     * @param bool $status
     * @param Customer $customer
     * @return Response
     */
    public function changeStatus(bool $status, Customer $customer)
    {
        //Declaration
        $response = false;

        //Process Request
        try {
            $response = $this->customerRepo->updateCustomer(['status' => $status], $customer);

        } catch (\Exception $e) {
            log_error(format_exception($e), $customer, 'update-customer-failed');
        }

        if (!$response)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = 'update-customer-status-successful';
        $auditMessage = 'You have successfully '.$changeAction.' a Customer with name: '.$customer->fullname;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function changePassword(array $params, Customer $customer)
    {
        //Declaration
        $res = false;

        //Process Request
        try {
            if (!(Hash::check($params['current-password'], $customer->password))) {
                // The passwords matches
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Your current password does not match with the password you provided.";
                return $this->response;
            }

            if(strcmp($params['current-password'],$params['new-password']) == 0){
                //Current password and new password are same
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "New Password cannot be same as your current password.";
                return $this->response;
            }

            $res = $this->customerRepo->updateCustomer([
                'password' => $params['new-password'],
                'ask_password_reset' => 0,
                'last_password_reset' => Carbon::now()
            ], $customer);
        } catch (\Exception $e) {
            log_error(format_exception($e), $customer, "change-customer-password-failed");
        }

        //Check if User was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "change-customer-password-successful";
        $auditMessage = "Password successfully changed for " . $customer->fullname;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Customer $customer
     * @return Response
     */
    public function deleteCustomer(Customer $customer)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->customerRepo->deleteCustomer($customer);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $customer, 'delete-customer-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }
        //Audit Trail
        $logAction = 'delete-customer-successful';
        $auditMessage = 'You have successfully deleted Customer with name '.$customer->fullname;

        log_activity($auditMessage, $customer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function getCreateCustomer(){
        return [
            'gender' => Constants::GENDER,
            'customers' => $this->listCustomers(),
            'customer' => $this->listCustomers()->first()??new Customer(),
            'titles' => Constants::TITLE,
        ] ;
    }
}
