<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\PropertyTypeResource;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IPropertyTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class CommonController extends MobileController
{
    /**
     * @var IPropertyTypeService
     */
    private IPropertyTypeService $propertyTypeService;

    /**
     * CategoryController constructor.
     *
     * @param IPropertyTypeService $propertyTypeService
     */
    public function __construct(IPropertyTypeService $propertyTypeService)
    {
        parent::__construct();
        $this->propertyTypeService = $propertyTypeService;
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


}
