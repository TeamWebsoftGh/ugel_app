<?php

namespace App\Services\Properties;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Common\NumberGenerator;
use App\Models\Property\Property;
use App\Repositories\Property\Interfaces\IPropertyRepository;
use App\Services\Helpers\Response;
use App\Services\Properties\Interfaces\IPropertyService;
use App\Services\ServiceBase;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PropertyService extends ServiceBase implements IPropertyService
{
    use UploadableTrait;
    private IPropertyRepository $propertyRepo;

    /**
     * PropertyService constructor.
     * @param IPropertyRepository $property
     */
    public function __construct(IPropertyRepository $property)
    {
        parent::__construct();
        $this->propertyRepo = $property;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return
     */
    public function listProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*'])
    {
        return $this->propertyRepo->listProperties($filter, $orderBy, $sortBy);
    }

    public function listLeaseProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*'])
    {
        $filter['property_purpose_id'] = 1;
        $filter['filter_has_units'] = true;
        return $this->propertyRepo->listProperties($filter, $orderBy, $sortBy);
    }

    public function listOwnProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*'])
    {
        $filter['property_purpose_id'] = 2;
        $filter['filter_has_units'] = true;
        return $this->propertyRepo->listProperties($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createProperty(array $params)
    {
        //Declaration
        $property = null;
        try{
            if(!isset($params['code'])){
                $params['property_code'] = NumberGenerator::gen(Property::class);
            }
            //Prepare request
            if(isset($params['cover_image']) && $params['cover_image']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['cover_image'], Str::slug($params['property_code']), 'properties');
            }
            $property = $this->propertyRepo->createProperty($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Property(), 'create-property-failed');
        }

        //Check if Successful
        if ($property == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $property, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $property;

        return $this->response;
    }


    /**
     * @param array $data
     * @param Property $property
     * @return Response
     */
    public function updateProperty(array $params, Property $property)
    {
        //Declaration
        $result = false;
        try{
            //Prepare request
            if(isset($params['cover_image']) && $params['cover_image']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['cover_image'], Str::slug($property->property_code), 'properties');
            }

            $result = $this->propertyRepo->updateProperty($params, $property);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new Property(), 'create-property-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $property, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $property;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Property|null
     */
    public function findPropertyById(int $id)
    {
        return $this->propertyRepo->findPropertyById($id);
    }


    /**
     * @param Property $property
     * @return Response
     */
    public function deleteProperty(Property $property)
    {
        //Declaration
        $result = false;

        if($property->propertyUnits()->count() > 0){
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        try{
            $result = $this->propertyRepo->deleteProperty($property);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $property, 'delete-property-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $property, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
