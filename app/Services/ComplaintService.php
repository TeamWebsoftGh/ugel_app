<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Models\Property\Complaint;
use App\Notifications\ComplainAgainstNotify;
use App\Notifications\ComplaintFromNotify;
use App\Repositories\Interfaces\IComplaintRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IComplaintService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ComplaintService extends ServiceBase implements IComplaintService
{
    private IComplaintRepository $complaintRepo;

    /**
     * ComplaintService constructor.
     *
     * @param IComplaintRepository $complaintRepository
     */
    public function __construct(IComplaintRepository $complaintRepository)
    {
        parent::__construct();
        $this->complaintRepo = $complaintRepository;
    }

    /**
     * List all the Categories
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $except
     * @return Collection
     */
    public function listComplaints(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->complaintRepo->listComplaints($order, $sort);
    }

    /**
     * Create Complaint
     *
     * @param array $params
     *
     * @return Response
     */
    public function createComplaint(array $params)
    {
        //Declaration
        $complaint = null;

        //Process Request
        try {
            $complaint = $this->complaintRepo->createComplaint($params);

            $notifiable_against = User::findOrFail($params['complaint_against']);

            $notifiable_from = User::findOrFail($params['complaint_from']);

            $notifiable_against->notify(new ComplainAgainstNotify($complaint->complaint_from_employee->fullname,$params['complaint_title']));
            $notifiable_from->notify(new ComplaintFromNotify($complaint->complaint_against_employee->fullname,$params['complaint_title']));

        } catch (\Exception $e) {
            log_error(format_exception($e), new Complaint(), 'create-complaint-failed');
        }

        //Check if Complaint was created successfully
        if (!$complaint)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-complaint-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $complaint, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $complaint;

        return $this->response;
    }


    /**
     * Find the Complaint by id
     *
     * @param int $id
     *
     * @return Complaint
     */
    public function findComplaintById(int $id)
    {
        return $this->complaintRepo->findComplaintById($id);
    }


    /**
     * Update Complaint
     *
     * @param array $params
     *
     * @param Complaint $complaint
     * @return Response
     */
    public function updateComplaint(array $params, Complaint $complaint)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->complaintRepo->updateComplaint($params, $complaint);
            $notifiable_against = User::find($params['complaint_against']);
            $notifiable_from = User::find($params['complaint_from']);

            $notifiable_against->notify(new ComplainAgainstNotify($complaint->complaint_from_employee->fullname,$params['complaint_title']));
            $notifiable_from->notify(new ComplaintFromNotify($complaint->complaint_against_employee->fullname,$params['complaint_title']));

        } catch (\Exception $e) {
            log_error(format_exception($e), $complaint, 'update-complaint-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-complaint-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $complaint, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param Complaint $complaint
     * @return Response
     */
    public function deleteComplaint(Complaint $complaint)
    {
        //Declaration
        $result =false;

        try{
            $result = $this->complaintRepo->deleteComplaint($complaint);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $complaint, 'create-complaint-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-complaint-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $complaint, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
