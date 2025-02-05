<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\PropertyCategoryResource;
use App\Http\Resources\PropertyTypeResource;
use App\Services\Interfaces\Properties\IPropertyCategoryService;
use App\Services\Interfaces\Properties\IPropertyTypeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class CommonController extends MobileController
{
    /**
     * @var IPropertyTypeService
     */
    private IPropertyTypeService $propertyTypeService;
    private IPropertyCategoryService $propertyCategoryService;

    /**
     * CategoryController constructor.
     *
     * @param IPropertyTypeService $propertyTypeService
     * @param IPropertyCategoryService $propertyCategoryService
     */
    public function __construct(IPropertyTypeService $propertyTypeService, IPropertyCategoryService  $propertyCategoryService)
    {
        parent::__construct();
        $this->propertyTypeService = $propertyTypeService;
        $this->propertyCategoryService = $propertyCategoryService;
    }

    /**
     * @throws Exception
     */
    public function propertyTypes(Request $request)
    {
        $data = $request->all();
        $items = $this->propertyTypeService->listPropertyTypes($data);

        $item = PropertyTypeResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function propertyTypeDetails(int $id)
    {
        $item = $this->propertyTypeService->findPropertyTypeById($id);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, new PropertyTypeResource($item));
    }

    public function propertyCategories(Request $request)
    {
        $data = $request->all();
        $items = $this->propertyCategoryService->listPropertyCategories($data);

        $item = PropertyCategoryResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function propertyCategoryDetails(int $id)
    {
        $item = $this->propertyCategoryService->findPropertyCategoryById($id);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, new PropertyCategoryResource($item));
    }


}
