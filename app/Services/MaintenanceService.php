<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\NewTicketCommentEvent;
use App\Events\TicketStatusChangeEvent;
use App\Mail\Tickets\TicketStatusChangeMail;
use App\Models\Common\Comment;
use App\Models\Common\DocumentUpload;
use App\Models\Common\NumberGenerator;
use App\Models\CustomerService\MaintenanceRequest;
use App\Services\Helpers\PropertyHelper;
use App\Traits\TaskUtil;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IMaintenanceRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IMaintenanceService;
use App\Traits\WorkflowUtil;

class MaintenanceService extends ServiceBase implements IMaintenanceService
{
    use UploadableTrait, WorkflowUtil;

    private IMaintenanceRepository $maintenanceRepo;

    /**
     * MaintenanceService constructor.
     *
     * @param IMaintenanceRepository $maintenanceRepository
     */
    public function __construct(IMaintenanceRepository $maintenanceRepository)
    {
        parent::__construct();
        $this->maintenanceRepo = $maintenanceRepository;
    }

    /**
     * List all the Maintenances
     *
     * @param string $order
     * @param string $sort
     *
     * @return
     */
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->maintenanceRepo->listMaintenances($filter, $order, $sort);
    }

    /**
     * Create the Maintenances
     *
     * @param array $params
     * @return Response
     */
    public function createMaintenance(array $params)
    {
        //Declaration
        $maintenance = null;

        //Process Request
        try {
            $params['reference'] = NumberGenerator::gen(MaintenanceRequest::class);
           // $params['user_id'] = user()->id;
            $params['status'] = 'opened';
            $maintenance = $this->maintenanceRepo->createMaintenance($params);

            send_mail(TicketStatusChangeMail::class, $maintenance, $maintenance->user);
        } catch (\Exception $e) {
            log_error(format_exception($e), new MaintenanceRequest(), 'create-maintenance-failed');
        }

        //Check if Maintenance was created successfully
        if (!$maintenance)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        $this->addWorkflowRequest($maintenance, $maintenance->user);
        //Audit Trail
        $logAction = 'create-maintenance-successful';
        $auditMessage = 'You have successfully added a new maintenance request: '.$maintenance->reference;

        log_activity($auditMessage, $maintenance, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $maintenance;

        return $this->response;
    }

    /**
     * Find the Maintenance by id
     *
     * @param int $id
     *
     * @return MaintenanceRequest
     */
    public function findMaintenanceById(int $id): MaintenanceRequest
    {
        return $this->maintenanceRepo->findMaintenanceById($id);
    }

    /**
     * Update Maintenance
     *
     * @param array $params
     * @param MaintenanceRequest $maintenance
     * @return Response
     */
    public function updateMaintenance(array $params, MaintenanceRequest $maintenance)
    {
        //Declaration
        $result = false;
        $oldStatus = $maintenance->status;

        //Process Request
        try {
            $result = $this->maintenanceRepo->updateMaintenance($params, $maintenance);

            if($oldStatus != $params['status']){
                event(new TicketStatusChangeEvent($maintenance));
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $maintenance, 'update-maintenance-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }
        $this->addWorkflowRequest($maintenance, $maintenance->user);
        //Audit Trail
        $logAction = 'update-maintenance-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $maintenance, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $maintenance;

        return $this->response;
    }

    /**
     * @param MaintenanceRequest $maintenance
     * @return Response
     */
    public function deleteMaintenance(MaintenanceRequest $maintenance)
    {
        //Declaration
        $result = false;
        try{

            $result = $this->maintenanceRepo->deleteMaintenance($maintenance);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $maintenance, 'delete-maintenance-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-maintenance-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $maintenance, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @return array
     */
    public function getCreateMaintenance()
    {
        $task = new MaintenanceRequest();

        return [
            'priorities' => TaskUtil::getPriorities(),
            'categories' => TaskUtil::getMaintenanceCategories(),
            'maintenance' => $task,
            'statuses' => TaskUtil::getAllStatuses(),
        ];
    }

    /**
     * @param array $data
     * @param MaintenanceRequest $ticket
     * @return Response
     */
    public function postComment(array $data, MaintenanceRequest $ticket)
    {
        //Declaration
        $result = false;

        try{
            $data['user_id'] = user()->id;
            $result = $ticket->comments()->create($data);
            event(new NewTicketCommentEvent($result));

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'add-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'add-comment-successful';
        $auditMessage = "Comment added successfully.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Comment $comment
     * @param MaintenanceRequest $ticket
     * @return Response
     */
    public function deleteComment(Comment $comment, MaintenanceRequest $ticket): Response
    {
        //Declaration
        $result = false;

        try{
            $result = $comment->delete();

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'delete-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-comment-successful';
        $auditMessage = "Comment deleted.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $data
     * @param MaintenanceRequest $ticket
     * @return Response
     */
    public function uploadDocument(array $data, MaintenanceRequest $ticket)
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
            $files = collect($data['attachments']);
            $result = $this->saveDocuments($files, $ticket, $ticket->reference);
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
     * @param MaintenanceRequest $ticket
     * @return Response
     */
    public function deleteDocument(DocumentUpload $document, MaintenanceRequest $ticket)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->maintenanceRepo->deleteDocument($document);

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $ticket, 'delete-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-document-successful';
        $auditMessage = "File deleted.";

        log_activity($auditMessage, $ticket, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

}
