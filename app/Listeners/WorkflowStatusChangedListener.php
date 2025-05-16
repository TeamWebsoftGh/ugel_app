<?php

namespace App\Listeners;

use App\Events\WorkflowStatusChanged;
use App\Models\Common\Email;
use Illuminate\Support\Str;

class WorkflowStatusChangedListener
{
    public function __construct()
    {
        //
    }

    public function handle(WorkflowStatusChanged $event)
    {
        $wf_request_detail = $event->workflowRequestDetail;
        $employee = $wf_request_detail->employee;
        $implementor = $wf_request_detail->implementor;
        $workflowRequest = $wf_request_detail->workflowRequest;
        $wf_type = optional($workflowRequest)->workflowType;
        $client = optional($workflowRequest)->client;

        if ($employee && $employee->email && $implementor && $implementor->email) {
            $data = [
                'emailable_type' => get_class($wf_request_detail),
                'emailable_id' => $wf_request_detail->id,
                'line_1' => "Your {$wf_type->name} has been {$wf_request_detail->status} by {$implementor->fullname}",
                'recipient_name' => $employee->fullname,
                'requestor_id' => $employee->id,
                'recipient_id' => $employee->id,
                'to' => $employee->email,
                'cc' => $client ? $implementor->email . ',' . $client->email : $implementor->email,
                'subject' => "REQUEST " . Str::upper($wf_request_detail->status),
                'company_id' => company_id(),
            ];

            Email::create($data);
        }
    }
}
