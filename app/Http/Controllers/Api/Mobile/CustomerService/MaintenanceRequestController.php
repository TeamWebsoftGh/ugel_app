<?php

namespace App\Http\Controllers\Api\Mobile\CustomerService;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Requests\MaintenanceRequestRequest;
use App\Http\Resources\MaintenanceCategoryResource;
use App\Http\Resources\MaintenanceRequestDetailsResource;
use App\Http\Resources\MaintenanceRequestResource;
use App\Models\Client\Client;
use App\Services\CustomerService\Interfaces\IMaintenanceCategoryService;
use App\Services\CustomerService\Interfaces\IMaintenanceService;
use App\Traits\TaskUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MaintenanceRequestController extends MobileController
{
    private IMaintenanceCategoryService $maintenanceCategoryService;
    private IMaintenanceService $maintenanceService;

    public function __construct(IMaintenanceCategoryService $maintenanceCategoryService, IMaintenanceService $maintenanceService)
    {
        parent::__construct();
        $this->maintenanceCategoryService = $maintenanceCategoryService;
        $this->maintenanceService = $maintenanceService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_client'] =  user()->client_id;
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $query = $this->maintenanceService->listMaintenances($data);

        if ($perPage < 0) {
            // Apply pagination if enabled
            $items = $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            // Return all records if pagination is disabled
            $items = $query->get();
        }

        $item = MaintenanceRequestResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function categories(Request $request)
    {
        $data = $request->all();
        $items = $this->maintenanceCategoryService->listMaintenanceCategories($data)->where('is_active', 1)->get();

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        $item = MaintenanceCategoryResource::collection($paginatedItems);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item, $paginatedItems);
    }


    public function lookup()
    {
        $client_id = user()->client_id;
        $customer = Client::with(['bookings' => function ($query) {
            $query->whereDate('lease_start_date', '<=', now())
                ->whereDate('lease_end_date', '>=', now())
                ->with(['property', 'propertyUnit', 'room']);
        }])->findOrFail($client_id);

        $bookings = $customer->bookings;

        $latestBooking = $bookings->sortByDesc('lease_start_date')->first();
        $data['categories'] = TaskUtil::getMaintenanceCategories();
        $data['subcategories'] = TaskUtil::getSubMaintenanceCategories();
        $data['booking'] = $latestBooking;

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }


    public function show($id)
    {
        $item = $this->maintenanceService->findMaintenanceById($id);
        $item = new MaintenanceRequestDetailsResource($item);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function store(MaintenanceRequestRequest $request)
    {
        $data = $request->except('_token', '_method', 'id');

        $data['client_id'] =  user()->client_id;
        $data['created_from'] = "api";
        $results = $this->maintenanceService->createMaintenance($data);

        if(isset($results->data))
        {
            $results->data = new MaintenanceRequestDetailsResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function update(MaintenanceRequestRequest $request)
    {
        $data = $request->all();

        $maintenance = $this->maintenanceService->findMaintenanceById($request->id);
        $data['client_id'] =  user()->client_id;
        $results = $this->maintenanceService->updateMaintenance($data, $maintenance);

        if(isset($results->data))
        {
            $results->data = new MaintenanceRequestDetailsResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function postComment(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
            'maintenance_request_id' => 'required'
        ]);

        $maintenance = $this->maintenanceService->findMaintenanceById($request->maintenance_request_id);
        $data = $request->all();
        $data['user_id'] = user()?->id;
        $data['created_from'] = "api";
        $results = $this->maintenanceService->postComment($data, $maintenance);

        return $this->apiResponseJson($results);
    }

    public function deleteComment(Request $request)
    {
        $task = $this->maintenanceService->findMaintenanceById($request->maintenance_request_id);
        $comment = $task->comments()->findOrFail($request->comment_id);

        $result = $this->maintenanceService->deleteComment($comment, $task);

        return $this->apiResponseJson($result);
    }


    public function destroy(int $id)
    {
        $electionResult = $this->maintenanceService->findMaintenanceById($id);
        $results = $this->maintenanceService->deleteMaintenance($electionResult);

        return $this->apiResponseJson($results);
    }
}
