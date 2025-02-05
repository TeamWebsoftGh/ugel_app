<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\VoterResource;
use App\Services\Interfaces\IDelegateService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VoterController extends MobileController
{
    /**
     * @var IDelegateService
     */
    private IDelegateService $delegateService;

    /**
     * ElectionController constructor.
     *
     * @param IDelegateService $delegateService
     */
    public function __construct(IDelegateService $delegateService)
    {
        parent::__construct();
        $this->delegateService = $delegateService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all request data
        $data = $request->all();

        // Get the collection of polling stations
        $items = $this->delegateService->listDelegates($data);

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        // Transform the items using a resource collection
        $items = VoterResource::collection($paginatedItems);

        // Return the paginated response
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items, $paginatedItems);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = $this->delegateService->findDelegateById($id);
        $item = new VoterResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
