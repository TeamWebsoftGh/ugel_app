<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Client\ClientType;
use App\Repositories\Interfaces\IClientTypeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IClientTypeService;
use Illuminate\Support\Collection;

class ClientTypeService extends ServiceBase implements IClientTypeService
{
    private IClientTypeRepository $clientTypeRepo;

    /**
     * ClientTypeService constructor.
     * @param IClientTypeRepository $clientType
     */
    public function __construct(IClientTypeRepository $clientType)
    {
        parent::__construct();
        $this->clientTypeRepo = $clientType;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listClientTypes(array $filter = null, string $orderBy = 'id', string $sortBy = 'asc', array $columns = ['*']) : Collection
    {
        return $this->clientTypeRepo->listClientTypes($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createClientType(array $data)
    {
        //Declaration
        $clientType = null;
        try{
            $clientType = $this->clientTypeRepo->createClientType($data);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new ClientType(), 'create-client-types-failed');
        }

        //Check if Successful
        if ($clientType == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-client-types-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $clientType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $clientType;

        return $this->response;
    }


    /**
     * @param array $data
     * @param ClientType $clientType
     * @return Response
     */
    public function updateClientType(array $data, ClientType $clientType)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->clientTypeRepo->updateClientType($data, $clientType);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $clientType, 'update-client-types-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-client-types-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $clientType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $clientType;

        return $this->response;
    }


    /**
     * @param int $id
     * @return ClientType|null
     */
    public function findClientTypeById(int $id)
    {
        return $this->clientTypeRepo->findClientTypeById($id);
    }


    /**
     * @param ClientType $clientType
     * @return Response
     */
    public function deleteClientType(ClientType $clientType)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->clientTypeRepo->deleteClientType($clientType);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $clientType, 'delete-client-types-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-client-types-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $clientType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
