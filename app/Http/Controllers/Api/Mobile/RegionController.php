<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegionResource;
use App\Models\Settings\Region;
use App\Services\Helpers\DelegteHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegionController extends MobileController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = Region::all();

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        // Transform the items using a resource collection
        $items = RegionResource::collection($paginatedItems);

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
        //
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
