<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\PropertyResource;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ElectoralAreaController extends MobileController
{
    /**
     * @var IRoomService
     */
    private IRoomService $electoralAreaService;

    /**
     * CategoryController constructor.
     *
     * @param IRoomService $electoralAreaService
     */
    public function __construct(IRoomService $electoralAreaService)
    {
        parent::__construct();
        $this->electoralAreaService = $electoralAreaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $items = $this->electoralAreaService->listElectoralAreas($data);

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        $item = PropertyResource::collection($paginatedItems);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item, $paginatedItems);
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
        $item = $this->electoralAreaService->findElectoralAreaById($id);
        $item = new PropertyResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
