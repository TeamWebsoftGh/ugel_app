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
     * @param $user
     * @param null $routeName
     * @return void/false
     */
    public function addWorkflowRequest($class, $user, $propertyId = null, $routeName = null)
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
                'user_id' => $user?->id
            ], [
                'workflow_requestable_id' => $class->id,
                'workflow_requestable_type' => get_class($class),
                'action_type' => "approve",
                'current_flow_sequence' => 0,
                'workflow_type_id' => $workflowType->id,
                'company_id' => $user->company_id,
                'client_id' => $user->client_id,
                'property_id' => $propertyId,
                'status' => 'pending',
            ]);

            $data['class'] = $class;
            $data['status'] = "approved";
            $data['route'] = $routeName??$workflowType->approval_route;
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

    public function getImplementorsFromWorkflow($workflow, $user, $class = null)
    {
        $wf_position_type = WorkflowPositionType::find($workflow->workflow_position_type_id);

        return $this->getGeneralPositions($wf_position_type->code, $user, $class);
    }

    public function getGeneralPositions($code, $user, $class = null)
    {
        $subjectTypesToCodes = ['team-lead']; // Define which codes require special handling

        // Direct handling for specific codes without needing to loop or check multiple conditions.
        if ($code == "assignees" && $class !== null) {
            return User::whereIn('id', $class->assignees()->pluck('id')->unique()->toArray())->get();
        } elseif ($code == "team-lead")
        {
            return collect([$this->getSupervisor($user->id)]); // Ensure it returns a collection
        }
//       else if(in_array($code, $subjectTypesToCodes)) {
//            $subjectType = $this->getSubjectTypeFromCode($code);
//
//            // Assume we also have a way to get the subject_id based on the employee and code
//            $subjectId = $this->getSubjectIdFromEmployeeAndCode($user, $code);
//
//            $workflows = WorkflowPosition::where('subject_type', $subjectType)
//                ->where(['subject_id' => $subjectId, 'is_active' => 1])
//                ->get();
//        }
       else if($team = Team::firstWhere("code", $code)) {
           return $team->users;
       }
       else {
            // Fallback or default handling if the code is not in the special array
            $workflows = WorkflowPosition::whereHas('workflowPositionType', function ($query) use ($code) {
                $query->where('code', '=', $code);
            })->where('is_active', 1)->get();
        }

        // Extract employee_ids from the workflows
        $userIds = $workflows->pluck('user_id')->unique()->toArray();

        // Fetch all Employees matching the employee_ids
        $users = User::whereIn('id', $userIds)->get();

        return $users;
    }

    public function getSubjectIdFromEmployeeAndCode($user, $code)
    {
        // Placeholder logic: adjust based on your application's needs
        switch ($code) {
            case 'team-lead':
                return $user->team_id;
            default:
                return null;
        }
    }

    public function getSupervisor($user_id)
    {
        $user = User::find($user_id);
        $supervisor = User::find($user->supervisor_id);
        if (!$supervisor || $supervisor->id == $user->id) {
            $unitHead = $this->getGeneralPositions("unit-head", $user)->first();
            $hod = $this->getGeneralPositions("hod", $user)->first();
            $hrManager = $this->getGeneralPositions("hr-manager", $user)->first();

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
            $user = $workflowRequest->employee;

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
                $this->sendNextWorkflowRequest($workflowRequest, $user, $data);
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
                    $sms = "Hello {{$user->firstname}}! Your leave request has been approved";
                    $this->sendSMSViaTwilio($sms, $user->contact_no);
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
            $user = $workflowRequest->employee;

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

                $this->sendNextWorkflowRequest($workflowRequest, $user, $data);
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

    public function canAccessItem($user_id, $exempt = null)
    {
        if($user_id == user()->id){
            return true;
        }

        if($exempt == user()->id){
            return true;
        }

        if(user()->hasRole('developer|admin|it-admin')){
            return true;
        }

        $user = User::find($user_id);

        $result = WorkflowPosition::where('is_active', 1);

        $result = $result->where(function ($query) use ($user){
            return $query->where(['subject_type' => Subsidiary::class, 'subject_id' => $user->subsidiary_id]);
        })->orWhere(function ($query) use ($user){
            return $query->where(['subject_type' => Department::class, 'subject_id' => $user->department_id]);
        })->orWhere(function ($query) use ($user){
            return $query->where(['subject_type' => DepartmentUnit::class, 'subject_id' => $user->unit_id]);
        })->orWhere(function ($query) use ($user){
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
     * @param $user
     * @param $workflowRequest
     * @param $data
     * @return void
     */
    public function sendWorkflow($workflow, $user, $workflowRequest, $data): void
    {
        $implementors = $this->getImplementorsFromWorkflow($workflow, $user, $workflowRequest->workflow_requestable);

        $isFirst = true;

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

            event(new WorkflowRequestEvent($workflowRequestDetail, $isFirst));

            // Only true for the first iteration
            $isFirst = false;
        }

        if (count($implementors) > 0) {
            $workflowRequest->current_flow_sequence = $workflow->flow_sequence;
            $workflowRequest->workflow_id = $workflow->id;
            $workflowRequest->save();
        }
    }

}
