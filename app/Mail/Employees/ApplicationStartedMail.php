<?php

namespace App\Mail\Employees;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationStartedMail extends Mailable
{
    public $applicant;

    /**
     * Create a new message instance.
     * @param Applicant $applicant
     * @return void
     */
    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'applicant' => $this->applicant,
        ];

        return $this->subject('Application Started')
            ->markdown('emails.employees.register', $data);
    }
}
