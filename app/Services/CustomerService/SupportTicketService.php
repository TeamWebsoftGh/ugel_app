<?php

namespace App\Services\CustomerService;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\NewTicketCommentEvent;
use App\Events\TicketStatusChangeEvent;
use App\Mail\Tickets\TicketStatusChangeMail;
use App\Models\Common\DocumentUpload;
use App\Models\Common\NumberGenerator;
use App\Models\CustomerService\Comment;
use App\Models\CustomerService\SupportTicket;
use App\Repositories\CustomerService\Interfaces\ISupportTicketRepository;
use App\Services\CustomerService\Interfaces\ISupportTicketService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Traits\TaskUtil;
use App\Traits\UploadableTrait;

class SupportTicketService extends ServiceBase implements ISupportTicketService
{
    use UploadableTrait;

    private ISupportTicketRepository $supportTicketRepo;

    /**
     * SupportTicketService constructor.
     *
     * @param ISupportTicketRepository $supportTicketRepository
     */
    public function __construct(ISupportTicketRepository $supportTicketRepository)
    {
        parent::__construct();
        $this->supportTicketRepo = $supportTicketRepository;
    }

    /**
     * List all the SupportTickets
     *
     * @param string $order
     * @param string $sort
     *
     * @return
     */
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->supportTicketRepo->listSupportTickets($filter, $order, $sort);
    }

    /**
     * Create the SupportTickets
     *
     * @param array $params
     * @return Response
     */
    public function createSupportTicket(array $params)
    {
        //Declaration
        $supportTicket = null;

        //Process Request
        try {
            $params['ticket_code'] = NumberGenerator::gen(SupportTicket::class);
           // $params['user_id'] = user()->id;
            $params['status'] = 'opened';
            $supportTicket = $this->supportTicketRepo->createSupportTicket($params);

            if (isset($params['ticket_files'])) {
                $files = collect($params['ticket_files']);
                $this->saveDocuments($files, $supportTicket, $supportTicket->ticket_code);
            }

            if(isset($params['assigned_to']))
            {
                $supportTicket->assignees()->sync($params['assigned_to']);
            }
            send_mail(TicketStatusChangeMail::class, $supportTicket, $supportTicket->user);
        } catch (\Exception $e) {
            log_error(format_exception($e), new SupportTicket(), 'create-support-ticket-failed');
        }

        //Check if SupportTicket was created successfully
        if (!$supportTicket)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-support-ticket-successful';
        $auditMessage = 'You have successfully added a new Ticket: '.$supportTicket->ticket_code;

        log_activity($auditMessage, $supportTicket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $supportTicket;

        return $this->response;
    }

    /**
     * Find the SupportTicket by id
     *
     * @param int $id
     *
     * @return SupportTicket
     */
    public function findSupportTicketById(int $id): SupportTicket
    {
        return $this->supportTicketRepo->findSupportTicketById($id);
    }

    /**
     * Update SupportTicket
     *
     * @param array $params
     * @param SupportTicket $supportTicket
     * @return Response
     */
    public function updateSupportTicket(array $params, SupportTicket $supportTicket)
    {
        //Declaration
        $result = false;
        $oldStatus = $supportTicket->status;

        //Process Request
        try {
            $result = $this->supportTicketRepo->updateSupportTicket($params, $supportTicket);

            if(isset($params['assigned_to']))
            {
                $supportTicket->assignees()->sync($params['assigned_to']);
            }
            if (isset($params['ticket_files'])) {
                $files = collect($params['ticket_files']);
                $this->saveDocuments($files, $supportTicket, $supportTicket->ticket_code);
            }

            if($oldStatus != $params['status']){
                event(new TicketStatusChangeEvent($supportTicket));
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $supportTicket, 'update-support-ticket-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-support-ticket-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $supportTicket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $supportTicket;

        return $this->response;
    }

    /**
     * @param SupportTicket $supportTicket
     * @return Response
     */
    public function deleteSupportTicket(SupportTicket $supportTicket)
    {
        //Declaration
        $result = false;
        try{
            if (count($supportTicket->assignees) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Support Ticket.";

                return $this->response;
            }

            $result = $this->supportTicketRepo->deleteSupportTicket($supportTicket);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $supportTicket, 'delete-support-ticket-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-support-ticket-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $supportTicket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @return array
     */
    public function getCreateTicket()
    {
        $task = new SupportTicket();

        return [
            'priorities' => TaskUtil::getPriorities(),
            'ticket' => $task,
            'statuses' => TaskUtil::getAllStatuses(),
            'employees' => PropertyHelper::getAll(),
            'categories' => TaskUtil::getAllSupportTopics(),
        ];
    }

    /**
     * @param array $data
     * @param SupportTicket $ticket
     * @return Response
     */
    public function postComment(array $data, SupportTicket $ticket)
    {
        //Declaration
        $result = false;

        try{
//            if(!$this->canAccessTask($task))
//            {
//                $this->response->status = ResponseType::ERROR;
//                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;
//
//                return $this->response;
//            }
            $data['user_id'] = user()->id;
            $result = $ticket->comments()->create($data);
            event(new NewTicketCommentEvent($result));

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'add-ticket-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'add-ticket-comment-successful';
        $auditMessage = "Comment added successfully.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Comment $comment
     * @param SupportTicket $ticket
     * @return Response
     */
    public function deleteComment(Comment $comment, SupportTicket $ticket): Response
    {
        //Declaration
        $result = false;

        try{
//            if(!$this->canAccessTask($task))
//            {
//                $this->response->status = ResponseType::ERROR;
//                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;
//
//                return $this->response;
//            }
            $result = $comment->delete();

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'delete-ticket-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-ticket-comment-successful';
        $auditMessage = "Comment deleted.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $data
     * @param SupportTicket $ticket
     * @return Response
     */
    public function uploadDocument(array $data, SupportTicket $ticket)
    {
        //Declaration
        $result = false;

        try{
//            if(!$this->canAccessTask($task))
//            {
//                $this->response->status = ResponseType::ERROR;
//                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;
//
//                return $this->response;
//            }
            $files = collect($data['ticket_files']);
            $result = $this->saveDocuments($files, $ticket, $ticket->ticket_code);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'upload-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'upload-document-successful';
        $auditMessage = "Document uploaded successfully.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param DocumentUpload $document
     * @param SupportTicket $ticket
     * @return Response
     */
    public function deleteDocument(DocumentUpload $document, SupportTicket $ticket)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->supportTicketRepo->deleteDocument($document);

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'delete-ticket-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-document-successful';
        $auditMessage = "File deleted.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

}
