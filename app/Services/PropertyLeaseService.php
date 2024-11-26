<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\PropertyLease;
use App\Repositories\Interfaces\IPropertyLeaseRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPropertyLeaseService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertyLeaseService extends ServiceBase implements IPropertyLeaseService
{
    use UploadableTrait;
    private IPropertyLeaseRepository $propertyLeaseRepo;

    /**
     * PropertyLeaseService constructor.
     * @param IPropertyLeaseRepository $propertyLease
     */
    public function __construct(IPropertyLeaseRepository $propertyLease)
    {
        parent::__construct();
        $this->propertyLeaseRepo = $propertyLease;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPropertyLeases(array $filter = null, string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->propertyLeaseRepo->listPropertyLeases($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createPropertyLease(array $params)
    {
        //Declaration
        $propertyLease = null;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_leases');
            }
            $propertyLease = $this->propertyLeaseRepo->createPropertyLease($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyLease(), 'create-property-lease-failed');
        }

        //Check if Successful
        if ($propertyLease == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-leasee-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $propertyLease, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyLease;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PropertyLease $propertyLease
     * @return Response
     */
    public function updatePropertyLease(array $params, PropertyLease $propertyLease)
    {
        //Declaration
        $result = false;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_leases');
            }

            $result = $this->propertyLeaseRepo->updatePropertyLease($params, $propertyLease);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyLease(), 'create-property-lease-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-lease-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $propertyLease, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyLease;

        return $this->response;
    }


    /**
     * @param int $id
     * @return PropertyLease|null
     */
    public function findPropertyLeaseById(int $id)
    {
        return $this->propertyLeaseRepo->findPropertyLeaseById($id);
    }


    /**
     * @param PropertyLease $propertyLease
     * @return Response
     */
    public function deletePropertyLease(PropertyLease $propertyLease)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->propertyLeaseRepo->deletePropertyLease($propertyLease);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $propertyLease, 'delete-property-lease-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-lease-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $propertyLease, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
