<?php

namespace App\Listeners;

use App\Events\WorkflowRequestEvent;
use App\Mail\Employees\WorkflowRequestImplementorMail;
use App\Mail\Employees\WorkflowRequestRequestorMail;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkflowRequestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  WorkflowRequestEvent  $event
     * @return void
     */
    public function handle(WorkflowRequestEvent $event)
    {
        $wf_request_detail = $event->wf_request_detail;
        $employee = $wf_request_detail->employee;
        $implementor = $wf_request_detail->implementor;

        // send email to implementor
        if(isset($implementor))
        {
            send_mail(WorkflowRequestImplementorMail::class, $wf_request_detail, $implementor);
        }

        // send email to employee
        if(isset($employee))
        {
            send_mail(WorkflowRequestRequestorMail::class, $wf_request_detail, $employee);
        }
    }
}
