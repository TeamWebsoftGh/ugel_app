<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\PropertyType;
use App\Repositories\Interfaces\IPropertyTypeRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPropertyTypeService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertyTypeService extends ServiceBase implements IPropertyTypeService
{
    use UploadableTrait;
    private IPropertyTypeRepository $propertyTypeRepo;

    /**
     * PropertyTypeService constructor.
     * @param IPropertyTypeRepository $propertyType
     */
    public function __construct(IPropertyTypeRepository $propertyType)
    {
        parent::__construct();
        $this->propertyTypeRepo = $propertyType;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPropertyTypes(array $filter = null, string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->propertyTypeRepo->listPropertyTypes($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createPropertyType(array $params)
    {
        //Declaration
        $propertyType = null;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_types');
            }
            $propertyType = $this->propertyTypeRepo->createPropertyType($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyType(), 'create-property-type-failed');
        }

        //Check if Successful
        if ($propertyType == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $propertyType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyType;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PropertyType $propertyType
     * @return Response
     */
    public function updatePropertyType(array $params, PropertyType $propertyType)
    {
        //Declaration
        $result = false;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_types');
            }

            $result = $this->propertyTypeRepo->updatePropertyType($params, $propertyType);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyType(), 'create-property-type-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $propertyType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyType;

        return $this->response;
    }


    /**
     * @param int $id
     * @return PropertyType|null
     */
    public function findPropertyTypeById(int $id)
    {
        return $this->propertyTypeRepo->findPropertyTypeById($id);
    }


    /**
     * @param PropertyType $propertyType
     * @return Response
     */
    public function deletePropertyType(PropertyType $propertyType)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->propertyTypeRepo->deletePropertyType($propertyType);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $propertyType, 'delete-property-type-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $propertyType, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
