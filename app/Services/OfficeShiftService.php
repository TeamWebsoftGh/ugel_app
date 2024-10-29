<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Timesheet\OfficeShift;
use App\Repositories\Interfaces\IOfficeShiftRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IOfficeShiftService;
use Illuminate\Support\Collection;

class OfficeShiftService extends ServiceBase implements IOfficeShiftService
{
    private $OfficeShiftRepo;

    /**
     * OfficeShiftService constructor.
     * @param IOfficeShiftRepository $OfficeShift
     */
    public function __construct(IOfficeShiftRepository $OfficeShift)
    {
        parent::__construct();
        $this->OfficeShiftRepo = $OfficeShift;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listOfficeShifts(array $filter = [], string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->OfficeShiftRepo->listOfficeShifts($filter, $orderBy, $sortBy, $columns);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createOfficeShift(array $data)
    {
        //Declaration
        $OfficeShift = null;

        try{

            $OfficeShift = $this->OfficeShiftRepo->createOfficeShift($data);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new OfficeShift(), 'create-office-shift-failed');
       }

        //Check if Successful
        if (!$OfficeShift)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-office-shift-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $OfficeShift, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $OfficeShift;

        return $this->response;
    }


    /**
     * @param array $data
     * @param OfficeShift $OfficeShift
     * @return Response
     */
    public function updateOfficeShift(array $data, OfficeShift $OfficeShift)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->OfficeShiftRepo->updateOfficeShift($data, $OfficeShift);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $OfficeShift, 'update-office-shift-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-office-shift-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $OfficeShift, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $OfficeShift;

        return $this->response;
    }

    /**
     * @param int $id
     * @return OfficeShift|null
     */
    public function findOfficeShiftById($id): ?OfficeShift
    {
        return $this->OfficeShiftRepo->findOfficeShiftById($id);
    }

    /**
     * @param OfficeShift $OfficeShift
     * @return Response
     */
    public function deleteOfficeShift(OfficeShift $OfficeShift)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->OfficeShiftRepo->deleteOfficeShift($OfficeShift);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $OfficeShift, 'delete-office-shift-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-office-shift-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $OfficeShift, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
