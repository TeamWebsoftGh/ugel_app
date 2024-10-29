<?php

namespace App\Mail\Tasks;

use App\Models\Applicant;
use App\Models\ApplicantReferee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRefereeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $referee;
    public $url;

    /**
     * Create a new message instance.
     *
     * @param Applicant $applicant
     * @param ApplicantReferee $referee
     *
     * @return void
     */
    public function __construct(Applicant $applicant, ApplicantReferee $referee, $url=null)
    {
        $this->applicant = $applicant;
        $this->referee = $referee;
        $this->url = $url;
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
            'details' => $this->applicant->details,
            'referee' => $this->referee,
            'url' => $this->url
        ];

        return $this->subject('Request for Recommendation')
            ->markdown('emails.referees.referee', $data);
    }
}
