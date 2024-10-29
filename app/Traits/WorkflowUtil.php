<?php

namespace App\Traits;

use App\Constants\ResponseType;
use App\Events\WorkflowRequestEvent;
use App\Events\WorkflowStatusChanged;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Organization\Branch;
use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Models\Workflow\WorkflowRequest;
use App\Models\Workflow\WorkflowRequestDetail;
use App\Models\Workflow\WorkflowType;
use App\Services\Helpers\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Trait WorkflowUtil
{
    public function sendNextWorkflowRequest($workflowRequest, $employee, $data)
    {
        if(!$this->HasPendingRequests($workflowRequest))
        {
            $workflow = $this->getNextWorkflow($workflowRequest);

            if($workflow)
            {
                $workflowRequestDetail =  new WorkflowRequestDetail();
                $workflowRequestDetail->workflow_position_type_id = $workflow->workflow_position_type_id;
                $workflowRequestDetail->implementor_id = optional($this->getImplementorFromWorkflow($workflow, $employee, $workflowRequest->workflow_requestable))->id;
                $workflowRequestDetail->company_id = $workflowRequest->company_id;
                $workflowRequestDetail->employee_id = $employee->id;
                $workflowRequestDetail->workflow_id = $workflow->id;
                $workflowRequestDetail->status = 'pending';
                $workflowRequestDetail->workflow_request_id = $workflowRequest->id;
                $workflowRequestDetail->save();

                $workflowRequest->current_flow_sequence = $workflow->flow_sequence;
                $workflowRequest->workflow_id = $workflow->id;
                $workflowRequest->save();

                $workflowRequest->workflow_requestable->status = "pending";
                $workflowRequest->workflow_requestable->save();

                event(new WorkflowRequestEvent($workflowRequestDetail));
            }else{
                $workflowRequest->status = $data['status'];
                $workflowRequest->is_completed = 1;
                $workflowRequest->approved_at = $data['approved_at'] ?? Carbon::now();
                $workflowRequest->save();

                $workflowRequest->workflow_requestable->status = $data['status'];
                $workflowRequest->workflow_requestable->save();
            }
        }
    }

    /**
     * Returns Walk In Customer for a Business
     *
     * @param int $business_id
     *
     * @return array/false
     */
    public function addWorkflowRequest($class, $employee)
    {
        try {
            $workflowType = WorkflowType::firstWhere('subject_type', get_class($class));

            $workflowRequest = WorkflowRequest::firstOrCreate([
                'workflow_requestable_id' => $class->id,
                'workflow_requestable_type' => get_class($class),
                'employee_id' => $employee->id
            ], [
                'workflow_requestable_id' => $class->id,
                'workflow_requestable_type' => get_class($class),
                'employee_id' => $employee->id,
                'current_flow_sequence' => 0,
                'workflow_type_id' => $workflowType->id,
                'company_id' => company_id(),
                'created_by' => user()->id,
                'status' => 'pending',
            ]);

            $data['class'] = $class;
            $data['status'] = "approved";
            $data["approved_at"] = Carbon::now();

            $this->sendNextWorkflowRequest($workflowRequest, $employee, $data);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $class, 'workflow-request-failed');
        }
    }

    public function UpdateWorkflowRequest(WorkflowRequestDetail $detail, array $data)
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
                $this->sendNextWorkflowRequest($workflowRequest, $employee, $data);
            }else{
                $detail->workflowRequest->status = $data['status'];
                $detail->workflowRequest->is_completed = 1;
                $detail->workflowRequest->approved_at = $data['approved_at'] ?? Carbon::now();;
                $detail->workflowRequest->save();

                $workflowRequest->workflow_requestable->status = $data['status'];
                $workflowRequest->workflow_requestable->save();
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
               foreach ($wf_req_detail as $workflowRequestDetail){
                   $workflow = $workflowRequestDetail->workflow;
                   $workflowRequestDetail->workflow_position_type_id = $workflow->workflow_position_type_id;
                   $workflowRequestDetail->implementor_id = optional($this->getImplementorFromWorkflow($workflow, $employee, $workflowRequest->workflow_requestable))->id;
                   $workflowRequestDetail->company_id = $workflowRequest->company_id;
                   $workflowRequestDetail->save();

                   event(new WorkflowRequestEvent($workflowRequestDetail));
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


    /**
     * Returns the customer group
     *
     * @param int $business_id
     * @param int $customer_id
     *
     * @return object
     */
    public function getSupervisor($employee_id)
    {
        $employee = Employee::find($employee_id);
        $supervisor = Employee::find($employee->supervisor_id);

        if(!$supervisor || optional($supervisor)->id == $employee_id)
        {
            $supervisor = $this->getHod($employee_id);
        }

        if(!$supervisor || optional($supervisor)->id == $employee_id)
        {
            $supervisor = $this->getGeneralManager($employee_id);
        }

        if(!$supervisor || optional($supervisor)->id == $employee_id)
        {
            $supervisor = $this->getHR();
        }

        return $supervisor;
    }

    /**
     * Returns the contact info
     *
     * @param int $business_id
     * @param int $contact_id
     *
     * @return array
     */
    public function getHod($employee_id)
    {
        $employee = Employee::find($employee_id);

        $workflow =  WorkflowPosition::where('subject_type', Department::class)
            ->where(['subject_id' => $employee->department_id, 'is_active' => 1])
            ->select('*')->first();
        return Employee::find(optional($workflow)->employee_id);
    }

    public function getBranchManager($employee_id)
    {
        $employee = Employee::find($employee_id);

        $workflow =  WorkflowPosition::where('subject_type', Branch::class)
            ->where(['subject_id' => $employee->location_id, 'is_active' => 1])
            ->select('*')->first();
        return Employee::find(optional($workflow)->employee_id);
    }

    public function getGeneralManager($employee_id)
    {
        $employee = User::find($employee_id);

        $workflow =  WorkflowPosition::where('subject_type', Company::class)
            ->where(['subject_id' => $employee->company_id, 'is_active' => 1])
            ->select('*')->first();
        return Employee::find(optional($workflow)->employee_id);
    }

    public function getHR()
    {
        $workflow = WorkflowPosition::whereHas('workflowPositionType', function ($query) {
            return $query->where('code', '=', 'hr-manager');
        })->where('is_active', 1)->first();

        return Employee::find(optional($workflow)->employee_id);
    }

    public function getCeo()
    {
        $workflow = WorkflowPosition::whereHas('workflowPositionType', function ($query) {
            return $query->where('code', '=', 'ceo');
        })->where('is_active', 1)->first();

        return Employee::find(optional($workflow)->employee_id);
    }

    public function checkIfWorkflowIsComplete($workflowRequest)
    {
        //TODO Check if last request is approved.
        if($workflowRequest->is_completed){
            return true;
        }

        if(count($workflows) > 0)
        {
            return false;
        }else{
            $workflowRequest->is_completed = 1;
            $workflowRequest->approved_at = Carbon::now();
            $workflowRequest->save();
            return true;
        }
    }

    public function getNextWorkflow($workflowRequest)
    {
        return Workflow::where('workflow_type_id', $workflowRequest->workflow_type_id)
            ->where('is_active', 1)
            ->where('action', 'approve')
            ->where('flow_sequence', '>', $workflowRequest->current_flow_sequence)
            ->orderBy('flow_sequence', 'asc')
            ->first();
    }

    public function HasPendingRequests($workflowRequest)
    {
        $wf_req_detail =  $workflowRequest->workflowRequestDetails()
            ->whereIn('status', ['pending'])->get();
        if(count($wf_req_detail)>0){
            return true;
        }

        return false;
    }


    public function getImplementorFromWorkflow($workflow, $employee, $class = null)
    {
        $wf_position_type = WorkflowPositionType::find($workflow->workflow_position_type_id);
        if($wf_position_type->code == 'supervisor')
        {
            return $this->getSupervisor($employee->id);
        }

        if($wf_position_type->code == 'hod')
        {

            return $this->getHod($employee->id);
        }

        if($wf_position_type->code == 'branch-manager')
        {
            return $this->getBranchManager($employee->id);
        }

        if($wf_position_type->code == 'country-manager')
        {
            return $this->getGeneralManager($employee->id);
        }

        if($wf_position_type->code == 'reliever')
        {
            return Employee::find(optional($class)->reliever_id);
        }

        $wf_positions = WorkflowPosition::firstWhere(['workflow_position_type_id' => $wf_position_type->id, 'is_active' => 1]);

        return optional($wf_positions)->employee;
    }

    public function getContactQuery($business_id, $type, $contact_ids = [])
    {
        $query = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
            ->leftjoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
            ->where('contacts.business_id', $business_id);

        if ($type == 'supplier') {
            $query->onlySuppliers();
        } elseif ($type == 'customer') {
            $query->onlyCustomers();
        }
        if (!empty($contact_ids)) {
            $query->whereIn('contacts.id', $contact_ids);
        }

        $query->select([
            'contacts.*',
            'cg.name as customer_group',
            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid")
        ]);

        if (in_array($type, ['supplier', 'both'])) {
            $query->addSelect([
                DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_paid")
            ]);
        }

        if (in_array($type, ['customer', 'both'])) {
            $query->addSelect([
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid")
            ]);
        }
        $query->groupBy('contacts.id');

        return $query;
    }
}
