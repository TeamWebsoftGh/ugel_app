<?php

namespace App\Services;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Propertyunit;
use App\Repositories\PropertyUnitRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPropertyUnitService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertyUnitService extends ServiceBase implements IPropertyUnitService
{
    use UploadableTrait;
    private PropertyUnitRepository $propertyUnitRepo;

    /**
     * PropertyUnitService constructor.
     * @param PropertyUnitRepository $propertyUnit
     */
    public function __construct(PropertyUnitRepository $propertyUnit)
    {
        parent::__construct();
        $this->propertyUnitRepo = $propertyUnit;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPropertyUnits(array $filter = null, string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->propertyUnitRepo->listPropertyUnits($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createPropertyUnit(array $params)
    {
        //Declaration
        $propertyUnit = null;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_Units');
            }
            $propertyUnit = $this->propertyUnitRepo->createPropertyUnit($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyUnit(), 'create-property-Unit-failed');
        }

        //Check if Successful
        if ($propertyUnit == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-Unit-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $propertyUnit, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyUnit;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PropertyUnit $propertyUnit
     * @return Response
     */
    public function updatePropertyUnit(array $params, PropertyUnit $propertyUnit)
    {
        //Declaration
        $result = false;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_Units');
            }

            $result = $this->propertyUnitRepo->updatePropertyUnit($params, $propertyUnit);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyUnit(), 'create-property-Unit-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-Unit-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $propertyUnit, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyUnit;

        return $this->response;
    }


    /**
     * @param int $id
     * @return PropertyUnit|null
     */
    public function findPropertyUnitById(int $id)
    {
        return $this->propertyUnitRepo->findPropertyUnitById($id);
    }


    /**
     * @param PropertyUnit $propertyUnit
     * @return Response
     */
    public function deletePropertyUnit(PropertyUnit $propertyUnit)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->propertyUnitRepo->deletePropertyUnit($propertyUnit);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $propertyUnit, 'delete-property-Unit-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-Unit-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $propertyUnit, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
