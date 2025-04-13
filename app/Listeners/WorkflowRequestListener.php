<?php

namespace App\Listeners;

use App\Events\WorkflowRequestEvent;
use App\Models\Common\Email;

class WorkflowRequestListener
{
    public function __construct()
    {
        //
    }

    public function handle(WorkflowRequestEvent $event)
    {
        $wf_request_detail = $event->wf_request_detail;
        $send_employee = $event->send_employee;

        try {
            $employee = optional($wf_request_detail)->employee;
            $implementor = optional($wf_request_detail)->implementor;
            $workflowRequest = optional($wf_request_detail)->workflowRequest;
            $wf_type = optional($workflowRequest)->workflowType;

            // 1. Email to Implementor
            if ($implementor && $implementor->email) {
                $dataToImplementor = [
                    'emailable_type' => get_class($wf_request_detail),
                    'emailable_id' => $wf_request_detail->id,
                    'line_1' => "You have a {$wf_type->name} from {$employee->fullname} pending your approval",
                    'line_2' => "Kindly login to the facilities portal and approve it.",
                    'requestor_id' => $employee->id ?? null,
                    'recipient_name' => $implementor->fullname,
                    'recipient_id' => $implementor->id,
                    'to' => $implementor->email,
                    'button_name' => "View Request",
                    'button_url' => $wf_type && $wf_type->approval_route
                        ? route($wf_type->approval_route, $workflowRequest->workflow_requestable_id)
                        : null,
                    'subject' => "APPROVAL REQUIRED",
                    'company_id' => company_id(),
                ];

                Email::create($dataToImplementor);
            }

            // 2. Email to Employee (if flag is set)
            if ($employee && $send_employee && $employee->email) {
                $dataToEmployee = [
                    'emailable_type' => get_class($wf_request_detail),
                    'emailable_id' => $wf_request_detail->id,
                    'line_1' => "Your {$wf_type->name} has been submitted successfully.",
                    'line_2' => "It is pending the approval of {$implementor->fullname}",
                    'recipient_name' => $employee->fullname,
                    'requestor_id' => $employee->id,
                    'recipient_id' => $employee->id,
                    'to' => $employee->email,
                    'subject' => "REQUEST SUBMITTED",
                    'company_id' => company_id(),
                ];

                Email::create($dataToEmployee);
            }

        } catch (\Exception $exception) {
            log_error(format_exception($exception), $wf_request_detail, 'create-email-failed');
        }
    }
}
