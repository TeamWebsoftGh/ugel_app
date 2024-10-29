<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Timesheet\LeaveType;
use App\Repositories\Interfaces\ILeaveTypeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ILeaveTypeService;
use Illuminate\Support\Collection;

class LeaveTypeService extends ServiceBase implements ILeaveTypeService
{
    private ILeaveTypeRepository $leaveTypeRepo;

    /**
     * LeaveTypeService constructor.
     *
     * @param ILeaveTypeRepository $leaveTypeRepo
     */
    public function __construct(ILeaveTypeRepository $leaveTypeRepo){
        parent::__construct();
        $this->leaveTypeRepo = $leaveTypeRepo;
    }

    /**
     * List all the LeaveTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->leaveTypeRepo->listLeaveTypes($order, $sort, $columns);
    }

    /**
     * List all the LeaveTypes
     *
     * @param $id
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveTypesDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->findLeaveTypeById($id)->LeaveTypeDetail;
    }

    /**
     * Create LeaveType
     *
     * @param array $params
     *
     * @return Response
     */
    public function createLeaveType(array $params)
    {
        //Declaration
        $leaveType = null;

        //Process Request
        try {
            $leaveType = $this->leaveTypeRepo->createLeaveType($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new LeaveType(), 'create-leave-type-failed');
        }

        //Check if leave-type was created successfully
        if (!$leaveType)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-leave-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $leaveType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $leaveType;

        return $this->response;
    }


    /**
     * Find the LeaveType by id
     *
     * @param int $id
     *
     * @return LeaveType
     */
    public function findLeaveTypeById(int $id)
    {
        return $this->leaveTypeRepo->findLeaveTypeById($id);
    }


    /**
     * Update LeaveType
     *
     * @param array $params
     *
     * @param LeaveType $leaveType
     * @return Response
     */
    public function updateLeaveType(array $params, LeaveType $leaveType)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->leaveTypeRepo->updateLeaveType($params, $leaveType->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $leaveType, 'update-leave-type-failed');
        }

        //Check if LeaveType was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-leave-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $leaveType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param LeaveType $leaveType
     * @return Response
     */
    public function deleteLeaveType(LeaveType $leaveType)
    {
        //Declaration
        if ($this->leaveTypeRepo->deleteLeaveType($leaveType->id))
        {
            //Audit Trail
            $logAction = 'delete-leave-type-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $leaveType, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
    }


    /**
     * @param array $data
     * @param LeaveType $leaveType
     * @return Response
     */
    public function createUpdateLeaveTypeDetails(array $data, LeaveType $leaveType)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['leave_type_id'] = $leaveType->id;

            $result = LeaveTypeDetail::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $leaveType, 'update-leave-type-detail-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-leave-type-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

        log_activity($auditMessage, $result, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $result;

        return $this->response;
    }

    /**
     * @param int $id
     * @return Response
     */
    public function deleteLeaveTypeDetail(int $id)
    {
        //Declaration
        $result = false;
        $leaveTypeDetail = new LeaveTypeDetail();

        try{
            $leaveTypeDetail = LeaveTypeDetail::findorFail($id);
            $result = $leaveTypeDetail->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $leaveTypeDetail, 'delete-leave-type-detail-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-leave-type-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $leaveTypeDetail, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
