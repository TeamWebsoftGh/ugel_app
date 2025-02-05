<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Delegate\PollingStation;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IPollingStationRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPollingStationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PollingStationService extends ServiceBase implements IPollingStationService
{
    use UploadableTrait;

    private IPollingStationRepository $pollingStationRepo;

    /**
     * PollingStationService constructor.
     *
     * @param IPollingStationRepository $pollingStationRepository
     */
    public function __construct(IPollingStationRepository $pollingStationRepository)
    {
        parent::__construct();
        $this->pollingStationRepo = $pollingStationRepository;
    }

    /**
     * List all the PollingStations
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listPollingStations(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->pollingStationRepo->listPollingStations($filter, $order, $sort);
    }

    /**
     * Create the PollingStation
     *
     * @param array $params
     * @return Response
     */
    public function createPollingStation(array $params)
    {
        //Declaration
        $pollingStation = null;

        //Process Request
        try {
            $pollingStation = $this->pollingStationRepo->createPollingStation($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new PollingStation(), 'create-polling-station-failed');
        }

        //Check if PollingStation was created successfully
        if (!$pollingStation)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-polling-station-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $pollingStation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $pollingStation;

        return $this->response;
    }

    /**
     * Find the PollingStation by id
     *
     * @param int $id
     *
     * @return PollingStation
     */
    public function findPollingStationById(int $id): PollingStation
    {
        return $this->pollingStationRepo->findPollingStationById($id);
    }

    /**
     * Update PollingStation
     *
     * @param array $params
     * @param PollingStation $pollingStation
     * @return Response
     */
    public function updatePollingStation(array $params, PollingStation $pollingStation)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->pollingStationRepo->updatePollingStation($params, $pollingStation);
        } catch (\Exception $e) {
            log_error(format_exception($e), $pollingStation, 'update-polling-station-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-polling-station-successful';
        $auditMessage = 'You have successfully updated a Polling Station '.$pollingStation->name;

        log_activity($auditMessage, $pollingStation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $pollingStation;

        return $this->response;
    }

    /**
     * @param PollingStation $pollingStation
     * @return Response
     */
    public function deletePollingStation(PollingStation $pollingStation)
    {
        //Declaration
        $result = false;
        try{
            if (count($pollingStation->delegates) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

                return $this->response;
            }

            $result = $this->pollingStationRepo->deletePollingStation($pollingStation);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $pollingStation, 'delete-polling-station-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-polling-station-successful';
        $auditMessage = 'You have successfully deleted Polling Station '.$pollingStation->name;

        log_activity($auditMessage, $pollingStation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
