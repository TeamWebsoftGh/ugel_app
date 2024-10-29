<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\DesignationChange;
use App\Repositories\Interfaces\IDesignationChangeRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IDesignationChangeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DesignationChangeService extends ServiceBase implements IDesignationChangeService
{
    private IDesignationChangeRepository $designationChangeRepo;
    private IEmployeeRepository $employeeRepo;

    /**
     * DesignationChangeService constructor.
     * @param IDesignationChangeRepository $designationChange
     */
    public function __construct(IDesignationChangeRepository $designationChange, IEmployeeRepository $employee)
    {
        parent::__construct();
        $this->designationChangeRepo = $designationChange;
        $this->employeeRepo = $employee;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listDesignationChanges(array $filter = null, string $orderBy = 'id', string $sortBy = 'asc', array $columns = ['*']) : Collection
    {
        return $this->designationChangeRepo->listDesignationChanges($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createDesignationChange(array $params)
    {
        //Declaration
        $designationChange = null;
        DB::beginTransaction();
        try{
            $designationChange = $this->designationChangeRepo->createDesignationChange($params);
            $emp = $designationChange->employee;
            if(isset($emp))
            {
                $emp->designation_id = $designationChange->designation_to_id;
                $emp->save();
            }

            DB::commit();

//            $notifiable = User::find($params['employee_id']);
//            $notifiable->notify(new EmployeeDesignationChange($params['DesignationChange_title']));

        }catch (\Exception $ex){
            DB::rollback();
            log_error(format_exception($ex), new DesignationChange(), 'create-designation-change-failed');
        }

        //Check if Successful
        if ($designationChange == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-designation-change-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $designationChange, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $designationChange;

        return $this->response;
    }


    /**
     * @param array $data
     * @param DesignationChange $designationChange
     * @return Response
     */
    public function updateDesignationChange(array $params, DesignationChange $designationChange)
    {
        //Declaration
        $result = false;
        DB::beginTransaction();
        try{
            $result = $this->designationChangeRepo->updateDesignationChange($params, $designationChange);
            $emp = $designationChange->employee;
            if(isset($emp))
            {
                $emp->designation_id = $designationChange->designation_to_id;
                $emp->save();
            }
            DB::commit();

//            $notifiable = User::find($params['employee_id']);
//            $notifiable->notify(new EmployeeDesignationChange($params['DesignationChange_title']));

        }catch (\Exception $ex){
            DB::rollback();
            log_error(format_exception($ex), $designationChange, 'update-designation-change-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-designation-change-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $designationChange, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $designationChange;

        return $this->response;
    }


    /**
     * @param int $id
     * @return DesignationChange|null
     */
    public function findDesignationChangeById(int $id)
    {
        return $this->designationChangeRepo->findDesignationChangeById($id);
    }


    /**
     * @param DesignationChange $designationChange
     * @return Response
     */
    public function deleteDesignationChange(DesignationChange $designationChange)
    {
        //Declaration
        $result = false;
        $emp = $designationChange->employee;
        DB::beginTransaction();

        try{
            if(isset($emp))
            {
                $emp->designation_id = $designationChange->designation_from_id;
                $emp->save();
            }
            $result = $this->designationChangeRepo->deleteDesignationChange($designationChange);
            DB::commit();
        }catch (\Exception $ex){
            DB::rollback();
            log_error(format_exception($ex), $designationChange, 'delete-designation-change-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-designation-change-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $designationChange, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
