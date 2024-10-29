<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Travel;
use App\Repositories\Interfaces\ITravelRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ITravelService;
use Illuminate\Support\Collection;

class TravelService extends ServiceBase implements ITravelService
{
    private ITravelRepository $travelRepo;
    private IEmployeeRepository $employeeRepo;

    /**
     * SectionService constructor.
     * @param ITravelRepository $travel
     * @param IEmployeeRepository $employee
     */
    public function __construct(ITravelRepository $travel, IEmployeeRepository $employee)
    {
        parent::__construct();
        $this->travelRepo = $travel;
        $this->employeeRepo = $employee;
    }

    /**
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @return Collection
     */
    public function listTravels(array $filter, string $orderBy = 'id', string $sortBy = 'desc') : Collection
    {
        if(!user()->can("read-travels"))
        {
            $filter['employee_id'] = employee()->id;
        }
        return $this->travelRepo->listTravels($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @param bool $send_mail
     * @return Response
     */
    public function createTravel(array $params, bool $send_mail = true): Response
    {
        //Declaration
        $travel = null;
        try{
            //Prepare request
            $employee = $this->employeeRepo->listExitedEmployees()->where('id', '==', $params['employee_id']);

            if ($employee->count() > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Employee already exited.";

                return $this->response;
            }

            $travel = $this->travelRepo->createTravel($params);

//            if($travel->status != 'pending')
//            {
//                $notifiable = User::find($params['employee_id']);
//                $notifiable->notify(new EmployeeTravelStatus($travel->status));
//            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Travel(), 'create-employee-travel-failed');
        }

        //Check if Successful
        if ($travel == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-employee-travel-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $travel, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $travel;

        return $this->response;
    }

    /**
     * @param array $params
     * @param Travel $travel
     * @return Response
     */
    public function updateTravel(array $params, Travel $travel)
    {
        //Declaration
        $result = false;

        try{
            //Prepare request
            $result = $this->travelRepo->updateTravel($params, $travel);

//            if($travel->status != 'pending')
//            {
//                $notifiable = User::find($params['employee_id']);
//                $notifiable->notify(new EmployeeTravelStatus($travel->status));
//            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), $travel, 'update-employee-travel-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-employee-travel-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $travel, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $travel;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Travel|null
     */
    public function findTravelById(int $id) : Travel
    {
        return $this->travelRepo->findOneOrFail($id);
    }

    /**
     * @param Travel $travel
     * @return Response
     */
    public function deleteTravel(Travel $travel)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->travelRepo->deleteTravel($travel);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $travel, 'delete-employee-travel-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-employee-travel-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $travel, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findTravelByStaffId(string $staff_id)
    {
        return $this->travelRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }

}
