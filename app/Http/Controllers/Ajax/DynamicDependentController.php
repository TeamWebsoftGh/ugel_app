<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Auth\Team;
use App\Services\Interfaces\IConstituencyService;
use App\Services\Interfaces\IPollingStationService;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Http\Request;

class DynamicDependentController extends Controller
{
    /**
     * @var IConstituencyService
     */
    private IRoomService $electoralAreaService;

    /**
     * CategoryController constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
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


    public function fetchTeams(Request $request)
    {
        $data = Team::where('is_active', 1)->get();
        $output = '';
        foreach ($data as $row)
        {
            $output .= '<option value=' . $row->id . '>' . $row->name . '</option>';
        }

        return $output;
    }
}
