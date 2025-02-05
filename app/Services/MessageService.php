<?php

namespace App\Services;

use App\Constants\ResponseType;
use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IMessageService;
use Illuminate\Support\Collection as Support;
use Illuminate\Support\Str;

class MessageService implements IMessageService
{
    private $messageRepo;

    /**
     * Message Service constructor.
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepo = $messageRepository;
    }


    public function listMessage(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support
    {
        return $this->messageRepo->listMessages($order, $sort);
    }


    /**
     * Find Message
     *
     * @param int $id
     * @return Message
     */
    public function findMessageById(int $id): Message
    {
        return $this->messageRepo->findMessageById($id);
    }

    /**
     * @param string $slug
     * @return Message
     */
    public function findMessageBySlug(string $slug): Message
    {
        return $this->messageRepo->findOneByOrFail(['slug' => $slug]);
    }


    /**
     * @param array $params
     * @return Response
     */
    public function createMessage(array $params)
    {
        //Declaration
        $result = new Response();
        $Message = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['name']);
            $Message = $this->MessageRepo->createMessage($params);
//           if (isset($params['user_email']))
//           {
//               $params['email'] = $params['user_email'];
//               $params['password'] = Hash::make($params['password']);
//
//               $params['phone_number'] = "";
//               $Message->users()->create($params);
//           }

        } catch (\Exception $e) {
            log_error($e->getMessage(), new Message(), 'create-Message-failed');
        }

        //Check if Customer was created successfully
        if (!$Message || $Message == null)
        {
            $result->status = ResponseType::ERROR;
            $result->message = "An error occurred. Try Again Later";

            return $result;
        }

        //Audit Trail
        $logAction = 'create-Message-successful';
        $auditMessage = 'You have successfully added a new Message: '.$Message->name;

        log_activity($auditMessage, $Message, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;
        $result->data = $Message;

        return $result;
    }


    /**
     * @param array $params
     * @param Message $Message
     * @return Response
     */
    public function updateMessage(array $params, Message $message)
    {
        $response = false;
        $result = new Response();

        $params['slug'] = Str::slug($params['name']);

        //Process Request
        try {
            $response = $this->messageRepo->updateMessage($params, $message->id);

        } catch (\Exception $e) {
            log_error(format_exception($e), $message, 'update-Message-failed');
        }

        if (!$response)
        {
            $result->status = ResponseType::ERROR;
            $result->message = "An error occurred. Try Again Later";

            return $result;
        }

        //Audit Message
        $logAction = 'update-Message-successful';
        $auditMessage = 'You have successfully updated an Message with name ' . $message->name;

        log_activity($auditMessage, $message, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }


    /**
     * @param int $id
     * @return Response
     */
    public function changeMessageStatus(int $id)
    {
        $result = new Response();

        $Message = $this->findMessageById($id);
        $changeAction = $Message->status?'deactivated':'activated';

        try{
            $status = $Message->status?0:1;

//            foreach ($Message->customers as $user)
//            {
//                $user->status = $status;
//                $user->save();
//            }
            $Message->status = $status;
            $Message->save();
        }catch (\Exception $ex ){
            log_error($ex->getMessage(), $Message, 'update-Message-failed');
        }


        //Audit Message
        $logAction = 'update-Message-successful';
        $auditMessage = "You have successfully ".$changeAction." Message: " . $Message->name;

        log_activity($auditMessage, $Message, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }


    /**
     *  Delete an Message
     *
     * @param Message $Message
     * @return Response
     * @throws \Exception
     */
    public function deleteMessage(Message $Message)
    {
        $result = new Response();
        $response = false;

        try{
            //Delete all branches and Users under Message
//            foreach ($Message->customers as $customer)
//            {
//                $customer->delete();
//            }

            $response = $this->MessageRepo->deleteMessage($Message->id);

            return  $result;
        }catch (\Exception $ex){
            log_error($ex->getMessage(), $Message, 'delete-Message-failed');
        }

        if (!$response)
        {
            $result->status = ResponseType::ERROR;
            $result->message = "You cannot delete this Message";

            return $result;
        }

        //Audit Delete Message
        $logAction = 'delete-Message-successful';
        $auditMessage = 'You have successfully deleted an Message with name ' . $Message->name;

        log_activity($auditMessage, $Message, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }

    public function listWriterMessages(int $writerId, string $order = 'id', string $sort = 'desc'): Support
    {
        return $this->messageRepo->listWriterMessages($writerId, $order, $sort);
    }

    public function listCustomerMessages(int $customerId, string $order = 'id', string $sort = 'desc'): Support
    {
        return $this->messageRepo->listCustomerMessages($customerId, $order, $sort);
    }

    public function listUserMessages(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support
    {
        return $this->messageRepo->listMessagesByCategory(5);
    }

    public function listOrderMessages(int $orderId, string $order = 'id', string $sort = 'desc'): Support
    {
        return $this->messageRepo->listMessages()->where("order_id", "==", $orderId);
    }
}
