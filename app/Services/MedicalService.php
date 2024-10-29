<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Medical;
use App\Repositories\Interfaces\IMedicalRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IMedicalService;
use Illuminate\Support\Collection;

class MedicalService extends ServiceBase implements IMedicalService
{
    private IMedicalRepository $medicalRepo;

    /**
     * SectionService constructor.
     * @param IMedicalRepository $medical
     * @param IEmployeeRepository $employee
     */
    public function __construct(IMedicalRepository $medical)
    {
        parent::__construct();
        $this->medicalRepo = $medical;
    }

    /**
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @return Collection
     */
    public function listMedicals(array $filter, string $orderBy = 'id', string $sortBy = 'desc') : Collection
    {
        if(!user()->can("read-payments"))
        {
            $filter['employee_id'] = employee()->id;
        }
        return $this->medicalRepo->listMedicals($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createMedical(array $params): Response
    {
        //Declaration
        $medical = null;
        try{
            $medical = $this->medicalRepo->createMedical($params);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new Medical(), 'create-employee-medical-failed');
        }

        //Check if Successful
        if ($medical == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-employee-medical-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $medical, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $medical;

        return $this->response;
    }

    /**
     * @param array $params
     * @param Medical $medical
     * @return Response
     */
    public function updateMedical(array $params, Medical $medical)
    {
        //Declaration
        $result = false;

        try{
            //Prepare request
            $result = $this->medicalRepo->updateMedical($params, $medical);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $medical, 'update-employee-medical-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-employee-medical-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $medical, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $medical;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Medical|null
     */
    public function findMedicalById(int $id) : Medical
    {
        return $this->medicalRepo->findOneOrFail($id);
    }

    /**
     * @param Medical $medical
     * @return Response
     */
    public function deleteMedical(Medical $medical)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->medicalRepo->deleteMedical($medical);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $medical, 'delete-employee-medical-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-employee-medical-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $medical, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findMedicalByStaffId(string $staff_id)
    {
        return $this->medicalRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }

}
