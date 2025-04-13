<?php

namespace App\Utilities;

use App\Constants\ResponseType;
use App\Events\WorkflowRequestEvent;
use App\Events\WorkflowStatusChanged;
use App\Models\Auth\Team;
use App\Models\Auth\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Models\Workflow\WorkflowRequest;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Models\Workflow\WorkflowType;
use App\Services\Helpers\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Trait WorkflowUtil
{
    /**
     * Returns Walk In Customer for a Business
     *
     * @param $class
     * @param $employee
     * @param null $routeName
     * @return void/false
     */
    public function addWorkflowRequest($class, $user)
    {
        // Check if the workflow feature is enabled and if the class status allows for workflow processing
        if (!config('app.enable_workflow', true) || in_array($class->status ?? '', ['approved', 'declined', 'rejected'], true)) {
            return;
        }

        try {
            $workflowType = WorkflowType::firstWhere('subject_type', get_class($class));
            $workflowRequest = WorkflowRequest::firstOrCreate([
                'workflow_requestable_id' => $class->id,
                'workflow_requestable_type' => get_class($class),
                'user_id' => $user->id
            ], [
                'workflow_requestable_id' => $class->id,
                'workflow_requestable_type' => get_class($class),
                'action_type' => "approve",
                'current_flow_sequence' => 0,
                'workflow_type_id' => $workflowType->id,
                'company_id' => $user->company_id,
                'status' => 'pending',
            ]);

            $data['class'] = $class;
            $data['status'] = "approved";
            $data['route'] = $routeName;
            $data["approved_at"] = Carbon::now();

            $this->sendNextWorkflowRequest($workflowRequest, $user, $data);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $class, 'workflow-request-failed');
        }
    }

    public function hasPendingRequests($workflowRequest)
    {
        // Initialize the query on workflowRequestDetails.
        $wf_req_details_query = $workflowRequest->workflowRequestDetails();

        // Check if there are any workflow request details at all
        if ($wf_req_details_query->count() === 0) {
            return false; // or true, depending on your definition of "pending" when there are no records
        }

        // Narrow down the query if flow_sequence is set.
        $wf_req_details_query = $wf_req_details_query->where('flow_sequence', $workflowRequest->current_flow_sequence);

        // If all_required is true or flow_sequence is not set, check for any pending requests.
        if ($workflowRequest->all_required)
        {
            // Check if there are any details with 'pending' status directly.
            $hasPending = $wf_req_details_query->where('status', 'pending')->exists();
        } else {
            // If not all_required and flow_sequence is set, check if all details are either approved or rejected.
            // This means, if there's none that are approved or rejected, it implies there are pending requests.
            $hasNonPending = $wf_req_details_query->whereIn('status', ['approved', 'rejected', 'declined'])->exists();
            // If there are non-pending (approved/rejected/declined) requests, then there's no pending request.
            $hasPending = !$hasNonPending;

        }
        return $hasPending;
    }

    public function sendNextWorkflowRequest($workflowRequest, $user, $data)
    {
        if(!$this->hasPendingRequests($workflowRequest))
        {
            $workflows = $this->getNextWorkflow($workflowRequest);

            if ($workflows)
            {
                foreach($workflows as $workflow)
                {
                    $this->sendWorkflow($workflow, $user, $workflowRequest, $data);
                }
            }
            else
            {
                $workflowRequest->status = $data['status'];
                $workflowRequest->is_completed = 1;
                $workflowRequest->approved_at = $data['approved_at'] ?? Carbon::now();
                $workflowRequest->save();

                $workflowRequest->workflow_requestable->status = $data['status'];
                $workflowRequest->workflow_requestable->save();

            }
        }
    }

    //to support multiple workflow for one sequence
    public function getNextWorkflow($workflowRequest)
    {
        // First, determine the next smallest sequence greater than the current
        $nextSmallestSequence = Workflow::where('workflow_type_id', $workflowRequest->workflow_type_id)
            ->where('is_active', 1)
            ->where('flow_sequence', '>', $workflowRequest->current_flow_sequence)
            ->where('action', 'approve')
            ->min('flow_sequence'); // Get the minimum sequence that is greater

        if (is_null($nextSmallestSequence)) {
            return null; // No next workflow available
        }

        // Now retrieve all workflows with this sequence
        return Workflow::where('workflow_type_id', $workflowRequest->workflow_type_id)
            ->where('is_active', 1)
            ->where('flow_sequence', $nextSmallestSequence)
            ->where('action', 'approve')
            ->get(); // Get all records with the smallest next sequence
    }

    public function getImplementorsFromWorkflow($workflow, $employee, $class = null)
    {
        $wf_position_type = WorkflowPositionType::find($workflow->workflow_position_type_id);

        return $this->getGeneralPositions($wf_position_type->code, $employee, $class);
    }

    public function getGeneralPositions($code, $employee, $class = null)
    {
        $subjectTypesToCodes = ['hod', 'division-head', 'branch-manager', 'unit-head', 'country-manager']; // Define which codes require special handling

        // Direct handling for specific codes without needing to loop or check multiple conditions.
        if ($code == "assignees" && $class !== null) {
            return User::whereIn('id', $class->assignees()->pluck('id')->unique()->toArray())->get();
        } elseif ($code == "supervisor") {
            return collect([$this->getSupervisor($employee->id)]); // Ensure it returns a collection
        }


        if(in_array($code, $subjectTypesToCodes)) {
            $subjectType = $this->getSubjectTypeFromCode($code);

            // Assume we also have a way to get the subject_id based on the employee and code
            $subjectId = $this->getSubjectIdFromEmployeeAndCode($employee, $code);

            $workflows = WorkflowPosition::where('subject_type', $subjectType)
                ->where(['subject_id' => $subjectId, 'is_active' => 1])
                ->get();
        } else {
            // Fallback or default handling if the code is not in the special array
            $workflows = WorkflowPosition::whereHas('workflowPositionType', function ($query) use ($code) {
                $query->where('code', '=', $code);
            })->where('is_active', 1)->get();
        }

        // Extract employee_ids from the workflows
        $employeeIds = $workflows->pluck('employee_id')->unique()->toArray();

        // Fetch all Employees matching the employee_ids
        $employees = User::whereIn('id', $employeeIds)->get();

        return $employees;
    }

    public function getSubjectIdFromEmployeeAndCode($employee, $code)
    {
        // Placeholder logic: adjust based on your application's needs
        switch ($code) {
            case 'hod':
                return $employee->department_id;
            case 'country-manager':
                return $employee->company_id;
            case 'division-head':
                return $employee->subsidiary_id;
            case 'branch-manager':
                return $employee->location_id;
            case 'unit-head':
                return $employee->department_unit_id;
            case 'team-lead':
                return $employee->team_id;
            default:
                return null;
        }
    }

    public function getSubjectTypeFromCode($code)
    {
        // Example mapping, adjust according to your application's logic
        Log::info($code);
        $mapping = [
            'hod' => Department::class,
            'country-manager' => Company::class,
            'division-head' => Subsidiary::class,
            'unit-head' => DepartmentUnit::class,
            'branch-manager' => Location::class,
            'team-lead' => Team::class,
        ];

        return $mapping[$code] ?? null;
    }

    public function getSupervisor($employee_id)
    {
        $employee = User::find($employee_id);
        $supervisor = User::find($employee->supervisor_id);
        if (!$supervisor || $supervisor->id == $employee->id) {
            $unitHead = $this->getGeneralPositions("unit-head", $employee)->first();
            $hod = $this->getGeneralPositions("hod", $employee)->first();
            $hrManager = $this->getGeneralPositions("hr-manager", $employee)->first();

            $supervisor = $unitHead ?: $hod ?: $hrManager;

            Log::info("Sequence: $supervisor?->fullname");
        }
        return $supervisor; // Ensure a collection is returned, filtering out nulls.
    }

    public function updateWorkflowRequest(WorkflowRequestDetail $detail, array $data)
    {
        $result = new Response();
        $result->status = ResponseType::SUCCESS;
        try {
            $workflowRequest = $detail->workflowRequest;
            $employee = $workflowRequest->employee;

            if($data['status'] != $detail->status)
            {
                $detail->update($data);
                $result->message = "Request ".$data['status'];

                event(new WorkflowStatusChanged($detail));
            }else{
                $result->message = "Request already ".$data['status'];
            }

            $data['approved_at'] = $detail->approved_at;
            if($data['status'] == "approved")
            {
                $data['route'] = $detail->approval_route;
                $this->sendNextWorkflowRequest($workflowRequest, $employee, $data);
            }
            else
            {
                $detail->workflowRequest->status = $data['status'];
                $detail->workflowRequest->is_completed = 1;
                $detail->workflowRequest->approved_at = $data['approved_at'] ?? Carbon::now();;
                $detail->workflowRequest->save();

                $workflowRequest->workflow_requestable->status = $data['status'];
                $workflowRequest->workflow_requestable->save();

                if (company_code() == 'nicktc') {
                    $sms = "Hello {{$employee->firstname}}! Your leave request has been approved";
                    $this->sendSMSViaTwilio($sms, $employee->contact_no);
                }
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $detail->workflow_requestable, 'workflow-request-failed');
        }

        return $result;
    }

    public function resendWorkflowRequest(WorkflowRequest $workflowRequest)
    {
        $result = new Response();

        try {
            $employee = $workflowRequest->employee;

            $wf_req_detail =  $workflowRequest->workflowRequestDetails()
                ->whereIn('status', ['pending'])->get();

            if(count($wf_req_detail)>0)
            {
                foreach ($wf_req_detail as $workflowRequestDetail)
                {
                    event(new WorkflowRequestEvent($workflowRequestDetail, false));
                }
            }else{
                $data['status'] = "approved";
                $data["approved_at"] = Carbon::now();

                $this->sendNextWorkflowRequest($workflowRequest, $employee, $data);
            }
            $result->status = ResponseType::SUCCESS;
            $result->message = "Operation successful";
        }catch (\Exception $ex){
            $result->status = ResponseType::ERROR;
            $result->message = $ex->getMessage();
            log_error(format_exception($ex), $workflowRequest, 'resend-workflow-request-failed');
        }

        return $result;
    }

    public function canAccessItem($employee_id, $exempt = null)
    {
        if($employee_id == user()->id){
            return true;
        }

        if($exempt == user()->id){
            return true;
        }

        if(user()->hasRole('developer|admin|it-admin')){
            return true;
        }

        $employee = User::find($employee_id);

        $result = WorkflowPosition::where('is_active', 1);

        $result = $result->where(function ($query) use ($employee){
            return $query->where(['subject_type' => Subsidiary::class, 'subject_id' => $employee->subsidiary_id]);
        })->orWhere(function ($query) use ($employee){
            return $query->where(['subject_type' => Department::class, 'subject_id' => $employee->department_id]);
        })->orWhere(function ($query) use ($employee){
            return $query->where(['subject_type' => DepartmentUnit::class, 'subject_id' => $employee->unit_id]);
        })->orWhere(function ($query) use ($employee){
            return $query->whereHas('workflowPositionType', function ($query) {
                return $query->whereIn('code', ['ceo', 'hr-manager', 'finance-manager']);
            });
        })->pluck('employee_id')->toArray();

        if(in_array(user()->id, $result))
            return true;

        return false;
    }

    /**
     * @param $workflow
     * @param $employee
     * @param $workflowRequest
     * @param $data
     * @return void
     */
    public function sendWorkflow($workflow, $user, $workflowRequest, $data): void
    {
        $implementors = $this->getImplementorsFromWorkflow($workflow, $user, $workflowRequest->workflow_requestable);

        foreach ($implementors as $implementor) {
            $workflowRequestDetail = new WorkflowRequestDetail();
            $workflowRequestDetail->workflow_position_type_id = $workflow->workflow_position_type_id;
            $workflowRequestDetail->implementor_id = $implementor->id;
            $workflowRequestDetail->company_id = $workflowRequest->company_id;
            $workflowRequestDetail->user_id = $user->id;
            $workflowRequestDetail->workflow_id = $workflow->id;
            $workflowRequestDetail->flow_sequence = $workflow->flow_sequence;
            $workflowRequestDetail->approval_route = $data['route'] ?? null;
            $workflowRequestDetail->status = 'pending';
            $workflowRequestDetail->workflow_request_id = $workflowRequest->id;
            $workflowRequestDetail->save();

            event(new WorkflowRequestEvent($workflowRequestDetail));
        }

        if (count($implementors) > 0)
        {
            $workflowRequest->current_flow_sequence = $workflow->flow_sequence;
            $workflowRequest->workflow_id = $workflow->id;
            $workflowRequest->save();
        }
    }
}
