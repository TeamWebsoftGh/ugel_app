<?php

namespace App\Services\Properties;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\PropertyCategory;
use App\Repositories\Interfaces\IPropertyCategoryRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\Properties\IPropertyCategoryService;
use App\Services\ServiceBase;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertyCategoryService extends ServiceBase implements IPropertyCategoryService
{
    use UploadableTrait;
    private IPropertyCategoryRepository $propertyCategoryRepo;

    /**
     * PropertyCategoryService constructor.
     * @param IPropertyCategoryRepository $propertyCategory
     */
    public function __construct(IPropertyCategoryRepository $propertyCategory)
    {
        parent::__construct();
        $this->propertyCategoryRepo = $propertyCategory;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPropertyCategories(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->propertyCategoryRepo->listPropertyCategories($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createPropertyCategory(array $params)
    {
        //Declaration
        $propertyCategory = null;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_Categorys');
            }
            $propertyCategory = $this->propertyCategoryRepo->createPropertyCategory($params);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyCategory(), 'create-property-categories-failed');
        }

        //Check if Successful
        if ($propertyCategory == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-categories-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $propertyCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyCategory;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PropertyCategory $propertyCategory
     * @return Response
     */
    public function updatePropertyCategory(array $params, PropertyCategory $propertyCategory)
    {
        //Declaration
        $result = false;
        try{
            //Prepare request
            if(isset($params['photo']) && $params['photo']instanceof UploadedFile)
            {
                $params['image'] = $this->uploadPublic($params['photo'], Str::slug($params['short_name']), 'property_Categorys');
            }

            $result = $this->propertyCategoryRepo->updatePropertyCategory($params, $propertyCategory);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new PropertyCategory(), 'create-property-Category-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-Category-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $propertyCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $propertyCategory;

        return $this->response;
    }


    /**
     * @param int $id
     * @return PropertyCategory|null
     */
    public function findPropertyCategoryById(int $id)
    {
        return $this->propertyCategoryRepo->findPropertyCategoryById($id);
    }


    /**
     * @param PropertyCategory $propertyCategory
     * @return Response
     */
    public function deletePropertyCategory(PropertyCategory $propertyCategory)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->propertyCategoryRepo->deletePropertyCategory($propertyCategory);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $propertyCategory, 'delete-property-Category-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-Category-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $propertyCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function deleteMultiplePropertyCategories(array $ids)
    {
        //Declaration
        $result = $this->propertyCategoryRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
