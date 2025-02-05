<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IElectoralAreaService;
use App\Services\Interfaces\IPollingStationService;
use Illuminate\Http\Request;

class DynamicDependentController extends Controller
{
    /**
     * @var IConstituencyService
     */
    private IConstituencyService $constituencyService;
    private IElectoralAreaService $electoralAreaService;
    private IPollingStationService $pollingStationService;

    /**
     * CategoryController constructor.
     *
     * @param IConstituencyService $constituencyService
     */
    public function __construct(IConstituencyService $constituencyService,
                                IElectoralAreaService $electoralAreaService,  IPollingStationService $pollingStationService)
    {
        parent::__construct();
        $this->constituencyService = $constituencyService;
        $this->electoralAreaService = $electoralAreaService;
        $this->pollingStationService = $pollingStationService;
    }
    //
    public function getConstituencies(Request $request)
    {
        $value = $request->get('value');
        $data = $this->constituencyService->listConstituencies(['filter_status' => 1, 'filter_region' => $value]);
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->name . '</option>';
        }

        return $output;
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
