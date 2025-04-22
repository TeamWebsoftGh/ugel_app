<?php

namespace App\Http\Controllers\Ajax;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Models\Auth\Team;
use App\Models\CustomerService\MaintenanceCategory;
use App\Models\Workflow\Workflow;
use App\Services\Workflow\Interfaces\IWorkflowService;
use Illuminate\Http\Request;

class DynamicDependentController extends MobileController
{
    /**
     * @var DynamicDependentController
     */
    /**
     * CategoryController constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }
    //
    public function getReturnToOptions($workflowTypeId)
    {
        $workflows = Workflow::where('workflow_type_id', $workflowTypeId)
            ->orderBy('flow_sequence')
            ->get();

        return response()->json(
            $workflows->pluck('workflow_name', 'id')
        );
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
