<?php

namespace App\Mail\Employees;

use App\Models\WorkflowRequestDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkflowStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $wf_request_detail;

    /**
     * Create a new message instance.
     * @param WorkflowRequestDetail $wf_request_detail
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

        return $this->subject('Approval Information')
            ->markdown('emails.employees.status', $data)
            ->cc([$this->implementor]);
    }
}
