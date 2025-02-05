<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Delegate\ElectoralArea;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IElectoralAreaRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IElectoralAreaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ElectoralAreaService extends ServiceBase implements IElectoralAreaService
{
    use UploadableTrait;

    private IElectoralAreaRepository $electoralAreaRepo;

    /**
     * ElectoralAreaService constructor.
     *
     * @param IElectoralAreaRepository $electoral_areaRepository
     */
    public function __construct(IElectoralAreaRepository $electoral_areaRepository)
    {
        parent::__construct();
        $this->electoralAreaRepo = $electoral_areaRepository;
    }

    /**
     * List all the ElectoralAreas
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listElectoralAreas(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->electoralAreaRepo->listElectoralAreas($filter, $order, $sort);
    }

    /**
     * Create the ElectoralAreas
     *
     * @param array $params
     * @return Response
     */
    public function createElectoralArea(array $params)
    {
        //Declaration
        $electoral_area = null;

        //Process Request
        try {
            $electoral_area = $this->electoralAreaRepo->createElectoralArea($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new ElectoralArea(), 'create-electoral-area-failed');
        }

        //Check if ElectoralArea was created successfully
        if (!$electoral_area)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-electoral-area-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $electoral_area, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $electoral_area;

        return $this->response;
    }


    /**
     * Find the ElectoralArea by id
     *
     * @param int $id
     *
     * @return ElectoralArea
     */
    public function findElectoralAreaById(int $id): ElectoralArea
    {
        return $this->electoralAreaRepo->findElectoralAreaById($id);
    }

    /**
     * Update ElectoralArea
     *
     * @param array $params
     * @param ElectoralArea $electoral_area
     * @return Response
     */
    public function updateElectoralArea(array $params, ElectoralArea $electoral_area)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->electoralAreaRepo->updateElectoralArea($params, $electoral_area);
        } catch (\Exception $e) {
            log_error(format_exception($e), $electoral_area, 'update-electoral-area-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-electoral-area-successful';
        $auditMessage = 'You have successfully updated a ElectoralArea '.$electoral_area->ElectoralArea_name;

        log_activity($auditMessage, $electoral_area, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $electoral_area;

        return $this->response;
    }

    /**
     * @param ElectoralArea $electoral_area
     * @return Response
     */
    public function deleteElectoralArea(ElectoralArea $electoral_area)
    {
        //Declaration
        $result = false;
        try{
            if (count($electoral_area->polling_stations) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;;

                return $this->response;
            }

            $result = $this->electoralAreaRepo->deleteElectoralArea($electoral_area);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $electoral_area, 'delete-electoral-area-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-electoral-area-successful';
        $auditMessage = 'You have successfully deleted ElectoralArea '.$electoral_area->ElectoralArea_name;

        log_activity($auditMessage, $electoral_area, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
