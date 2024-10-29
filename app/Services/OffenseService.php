<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Offense;
use App\Repositories\Interfaces\IOffenseRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IOffenseService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;

class OffenseService extends ServiceBase implements IOffenseService
{
    use UploadableTrait;
    private IOffenseRepository $offenseRepo;

    /**
     * OffenseService constructor.
     * @param IOffenseRepository $offense
     */
    public function __construct(IOffenseRepository $offense)
    {
        parent::__construct();
        $this->offenseRepo = $offense;
    }

    /**
     * @param array $filter
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listOffenses(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        if(!user()->can("read-offenses"))
        {
            $filter['employee_id'] = employee()->id;
        }
        return $this->offenseRepo->listOffenses($filter, $order, $sort, $columns);
    }


    /**
     * @param array $data
     *
     * @return Response
     */
    public function createOffense(array $data)
    {
        //Declaration
        $offense = null;

        try{
            $offense = $this->offenseRepo->createOffense($data);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), new Offense(), 'create-offense-failed');
        }

        //Check if Successful
        if (!$offense)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-offense-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $offense, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $offense;

        return $this->response;
    }


    /**
     * @param array $data
     * @param Offense $offense
     * @return Response
     */
    public function updateOffense(array $data, Offense $offense)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->offenseRepo->updateOffense($data, $offense);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $offense, 'update-offense-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-offense-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $offense, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $offense;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Offense|null
     */
    public function findOffenseById(int $id)
    {
        return $this->offenseRepo->findOffenseById($id);
    }


    /**
     * @param Offense $offense
     * @return Response
     */
    public function deleteOffense(Offense $offense)
    {
        //Declaration
        $result = false;

        try{

            $result = $this->offenseRepo->deleteOffense($offense);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $offense, 'delete-offense-failed');
        }

        if (!$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-offense-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $offense, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
