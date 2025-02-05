<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\ClientResource;
use App\Http\Resources\PropertyResource;
use App\Models\Property\PropertyPurpose;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IPropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class PropertyController extends MobileController
{
    /**
     * @var IPropertyService
     */
    private IPropertyService $propertyService;

    /**
     * CategoryController constructor.
     *
     * @param IPropertyService $constituencyService
     */
    public function __construct(IPropertyService $propertyService)
    {
        parent::__construct();
        $this->propertyService = $propertyService;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $data = $request->all();
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $items = $this->propertyService->listProperties($data)->paginate($perPage, $page);

        $item = PropertyResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function show(Request $request, $id)
    {
        $property = $this->propertyService->findPropertyById($id);
        $item = new PropertyResource($property);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:constituencies,code,'.$request->input("id"),
            'region_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $item = $this->propertyService->findConstituencyById($request->input("id"));
            $results = $this->propertyService->updateConstituency($data, $item);
        }else{
            $results = $this->propertyService->createConstituency($data);
        }

        if(isset($results->data))
        {
            $results->data = new ClientResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $item = $this->propertyService->findConstituencyById($id);
        $results = $this->propertyService->deleteConstituency($item);

        return $this->apiResponseJson($results);
    }
}
