<?php

namespace App\Mail\Employees;

use App\Models\Employees\Leave;
use App\Models\WorkflowRequestDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkflowRequestImplementorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $wf_request_detail;
    public $wf_type;
    public $implementor;
    public $employee;

    /**
     * Create a new message instance.
     * @param Leave $leave
     */
    public function __construct(WorkflowRequestDetail $wf_request_detail)
    {
        $this->wf_request_detail = $wf_request_detail;
        $this->wf_type = $wf_request_detail->workflowRequest->workflowType;
        $this->implementor = $wf_request_detail->implementor;
        $this->employee = $wf_request_detail->employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'wf_type' => $this->wf_type,
            'wf_request_detail' => $this->wf_request_detail,
            'implementor' => $this->implementor,
            'employee' => $this->employee,
        ];

        return $this->subject('Approval Required')
            ->markdown('emails.employees.approval-required', $data);
    }
}
