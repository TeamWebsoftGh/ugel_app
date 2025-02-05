<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCategoryResource;
use App\Services\Interfaces\IPollingStationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PollingStationController extends MobileController
{
    /**
     * @var IPollingStationService
     */
    private IPollingStationService $pollingStationService;

    /**
     * PresidentialCandidateController constructor.
     *
     * @param IPollingStationService $pollingStationService
     */
    public function __construct(IPollingStationService $pollingStationService)
    {
        parent::__construct();
        $this->pollingStationService = $pollingStationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all request data
        $data = $request->all();

        // Get the collection of polling stations
        $items = $this->pollingStationService->listPollingStations($data);

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        // Transform the items using a resource collection
        $items = PropertyCategoryResource::collection($paginatedItems);

        // Return the paginated response
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items, $paginatedItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:polling_stations,name,',
            'code' => 'required|unique:polling_stations,code,',
            'electoral_area_id' => 'required',
        ]);

        $data = $request->all();
        $data['is_active']= 1;

        $results = $this->pollingStationService->createPollingStation($data);

        $results->data = new PropertyCategoryResource($results->data);
        return $this->apiResponseJson($results);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = $this->pollingStationService->findPollingStationById($id);
        $item = new PropertyCategoryResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|unique:polling_stations,name,'.$request->input("polling_station_id"),
            'code' => 'sometimes|unique:polling_stations,code,'.$request->input("polling_station_id"),
            'constituency_id' => 'sometimes',
        ]);

        $data = $request->all();

        $polling_station = $this->pollingStationService->findPollingStationById($request->input("polling_station_id"));
        $results = $this->pollingStationService->updatePollingStation($data, $polling_station);

        $results->data = new PropertyCategoryResource($results->data);
        return $this->apiResponseJson($results);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
