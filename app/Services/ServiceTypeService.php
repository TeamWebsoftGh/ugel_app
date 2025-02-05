<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\ServiceType;
use App\Repositories\Interfaces\IServiceTypeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IServiceTypeService;
use Illuminate\Support\Collection;

class ServiceTypeService extends ServiceBase implements IServiceTypeService
{
    private IServiceTypeRepository $serviceTypeRepo;

    /**
     * ServiceTypeService constructor.
     *
     * @param IServiceTypeRepository $serviceTypeRepository
     */
    public function __construct(IServiceTypeRepository $serviceTypeRepository)
    {
        parent::__construct();
        $this->serviceTypeRepo = $serviceTypeRepository;
    }

    /**
     * List all the ServiceTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listServiceTypes(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->serviceTypeRepo->listServiceTypes($filter, $order, $sort);
    }

    /**
     * Create ServiceType
     *
     * @param array $params
     *
     * @return Response
     */
    public function createServiceType(array $params)
    {
        //Declaration
        $serviceType = null;

        //Process Request
        try {
            $serviceType = $this->serviceTypeRepo->createServiceType($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new ServiceType(), 'create-service-type-failed');
        }

        //Check if ServiceType was created successfully
        if (!$serviceType)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-service-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $serviceType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $serviceType;

        return $this->response;
    }


    /**
     * Find the ServiceType by id
     *
     * @param int $id
     *
     * @return ServiceType
     */
    public function findServiceTypeById(int $id)
    {
        return $this->serviceTypeRepo->findServiceTypeById($id);
    }


    /**
     * Update ServiceType
     *
     * @param array $params
     *
     * @param ServiceType $serviceType
     * @return Response
     */
    public function updateServiceType(array $params, ServiceType $serviceType)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->serviceTypeRepo->updateServiceType($params, $serviceType);
        } catch (\Exception $e) {
            log_error(format_exception($e), $serviceType, 'update-service-type-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-service-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $serviceType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param ServiceType $serviceType
     * @return Response
     */
    public function deleteServiceType(ServiceType $serviceType)
    {
        //Declaration
        $result =false;

        try{
            $result = $this->serviceTypeRepo->deleteServiceType($serviceType);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $serviceType, 'create-service-type-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-service-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $serviceType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
