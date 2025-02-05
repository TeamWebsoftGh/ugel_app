<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Timesheet\Holiday;
use App\Repositories\Interfaces\IHolidayRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IHolidayService;
use Illuminate\Support\Collection;

class HolidayService extends ServiceBase implements IHolidayService
{
    private IHolidayRepository $holidayRepo;

    /**
     * SectionService constructor.
     * @param IHolidayRepository $holiday
     */
    public function __construct(IHolidayRepository $holiday)
    {
        parent::__construct();
        $this->holidayRepo = $holiday;
    }

    /**
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @return Collection
     */
    public function listHolidays(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->holidayRepo->listHolidays($filter, $orderBy, $sortBy, $columns);
    }

    /**
     * @param array $params
     *
     * @param bool $send_mail
     * @return Response
     */
    public function createHoliday(array $params, bool $send_mail = true): Response
    {
        //Declaration
        $holiday = null;
        try{
            //Prepare request
            $holiday = $this->holidayRepo->createHoliday($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Holiday(), 'create-holiday-failed');
        }

        //Check if Successful
        if ($holiday == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-holiday-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

        log_activity($auditMessage, $holiday, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $holiday;

        return $this->response;
    }

    /**
     * @param array $data
     * @param Holiday $holiday
     * @return Response
     */
    public function updateHoliday(array $data, Holiday $holiday)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->holidayRepo->updateHoliday($data, $holiday);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $holiday, 'update-holiday-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-holiday-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $holiday, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $holiday;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Holiday|null
     */
    public function findHolidayById(int $id) : Holiday
    {
        return $this->holidayRepo->findOneOrFail($id);
    }

    /**
     * @param Holiday $holiday
     * @return Response
     */
    public function deleteHoliday(Holiday $holiday)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->holidayRepo->deleteHoliday($holiday);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $holiday, 'delete-holiday-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-holiday-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $holiday, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findHolidayByStaffId(string $staff_id)
    {
        return $this->holidayRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }

}
