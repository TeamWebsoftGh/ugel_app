<?php

namespace App\Listeners;

use App\Events\WorkflowStatusChanged;
use App\Mail\Employees\WorkflowStatusChangedMail;
use App\Models\User;

class WorkflowStatusChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WorkflowStatusChanged  $event
     * @return void
     */
    public function handle(WorkflowStatusChanged $event)
    {
        $wf_request_detail = $event->workflowRequestDetail;
        $employee = $wf_request_detail->employee;

        // send email to employee
        if(isset($employee))
        {
            send_mail(WorkflowStatusChangedMail::class, $wf_request_detail, $employee);
        }
    }
}
