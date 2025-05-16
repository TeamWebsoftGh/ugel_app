<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\ClientResource;
use App\Http\Resources\PropertyDetailResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\PropertyUnitResource;
use App\Http\Resources\RoomResource;
use App\Services\Properties\Interfaces\IPropertyService;
use App\Services\Properties\Interfaces\IPropertyUnitService;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class PropertyController extends MobileController
{
    /**
     * @var IPropertyService
     */
    private IPropertyService $propertyService;
    private IPropertyUnitService $propertyUnitService;
    private IRoomService $roomService;

    /**
     * CategoryController constructor.
     *
     * @param IPropertyService $constituencyService
     */
    public function __construct(IPropertyService $propertyService, IPropertyUnitService $propertyUnitService, IRoomService $roomService)
    {
        parent::__construct();
        $this->propertyService = $propertyService;
        $this->propertyUnitService = $propertyUnitService;
        $this->roomService = $roomService;
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
        $query = $this->propertyService->listProperties($data);

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = PropertyResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = PropertyResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

    public function show(Request $request, $id)
    {
        $property = $this->propertyService->findPropertyById($id);
        $item = new PropertyDetailResource($property);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function units(Request $request)
    {
        // Get pagination inputs with defaults
        $perPage = $request->input('perPage', 25);
        $page = $request->input('page', 1); // Explicitly set page

        // Fetch paginated data directly from the query
        $query = $this->propertyUnitService
            ->listPropertyUnits($request->all());

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = PropertyUnitResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = PropertyUnitResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

    public function rooms(Request $request)
    {
        $perPage = $request->input('perPage', 25);
        $page = $request->input('page', 1);

        $query = $this->roomService->listRooms($request->all());

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = RoomResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = RoomResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

}
