<?php

namespace App\Http\Controllers\Ajax;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyUnitResource;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IPollingStationService;
use App\Services\Properties\Interfaces\IPropertyUnitService;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Http\Request;

class
DynamicPropertyController extends MobileController
{
    /**
     * @var IConstituencyService
     */
    private IPropertyUnitService $propertyUnitService;
    private IRoomService $electoralAreaService;
    private IPollingStationService $pollingStationService;

    /**
     * CategoryController constructor.
     *
     * @param IConstituencyService $constituencyService
     */
    public function __construct(IPropertyUnitService $propertyUnitService,
                                IRoomService         $electoralAreaService, IPollingStationService $pollingStationService)
    {
        parent::__construct();
        $this->propertyUnitService = $propertyUnitService;
        $this->electoralAreaService = $electoralAreaService;
        $this->pollingStationService = $pollingStationService;
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


    public function getPollingStations(Request $request)
    {
        $value = $request->get('value');
        $data = $this->pollingStationService->listPollingStations(['filter_status' => 1, 'filter_electoral_area' => $value]);
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->name . '</option>';
        }

        return $output;
    }
}
