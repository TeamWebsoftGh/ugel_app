<?php

namespace App\Mail\Employees;

use App\Models\Employees\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    /**
     * Create a new message instance.
     * @param Leave $leave
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'leave' => $this->leave,
            'employee' => $this->leave->employee,
        ];

        if(settings('notify_hrm', 0))
        {
            return $this->subject('Leave Approved')
                ->markdown('emails.employees.approve-leave-details', $data)->cc(settings("hrm_email"));
        }

        return $this->subject('Leave Approved')
            ->markdown('emails.employees.approve-leave-details', $data);
    }
}
