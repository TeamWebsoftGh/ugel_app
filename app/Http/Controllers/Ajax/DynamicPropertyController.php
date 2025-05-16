<?php

namespace App\Http\Controllers\Ajax;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyUnitResource;
use App\Models\Client\Client;
use App\Models\CustomerService\MaintenanceCategory;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IPollingStationService;
use App\Services\Properties\Interfaces\IPropertyUnitService;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Http\Request;

class DynamicPropertyController extends MobileController
{
    /**
     * @var IConstituencyService
     */
    private IPropertyUnitService $propertyUnitService;
    private IRoomService $electoralAreaService;

    /**
     * CategoryController constructor.
     *
     * @param IConstituencyService $constituencyService
     */
    public function __construct(IPropertyUnitService $propertyUnitService)
    {
        parent::__construct();
        $this->propertyUnitService = $propertyUnitService;
    }
    //
    public function units(Request $request)
    {
        $data = $request->all();
        $items = $this->propertyUnitService->listPropertyUnits($data);

        $item = PropertyUnitResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function getElectoralAreas(Request $request)
    {
        $value = $request->get('value');
        $data = $this->electoralAreaService->listElectoralAreas(['filter_status' => 1, 'filter_constituency' => $value]);
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->name . '</option>';
        }

        return $output;
    }


    public function getDetails($id)
    {
        $customer = Client::with(['bookings' => function ($query) {
            $query->whereDate('lease_start_date', '<=', now())
                ->whereDate('lease_end_date', '>=', now())
                ->with(['property', 'propertyUnit', 'room']);
        }])->findOrFail($id);

        $bookings = $customer->bookings;

        $latestBooking = $bookings->sortByDesc('lease_start_date')->first();

        return response()->json([
            'client_number' => $customer->client_number,
            'client_phone_number' => $customer->phone_number,
            'client_email' => $customer->email,
            'selected' => [
                'property_id' => $latestBooking->property_id ?? null,
                'property_unit_id' => $latestBooking->property_unit_id ?? null,
                'room_id' => $latestBooking->room_id ?? null,
            ],
        ]);
    }


    public function getMaintenanceCategories($id = null)
    {
        $category = MaintenanceCategory::with('subcategories')->findOrFail($id);

        return response()->json(
            $category->subcategories->pluck('name', 'id')
        );
    }

}
