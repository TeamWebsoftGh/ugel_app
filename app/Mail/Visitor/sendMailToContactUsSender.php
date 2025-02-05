<?php

namespace App\Mail\Visitor;

use App\Models\CustomerService\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMailToContactUsSender extends Mailable
{
    use Queueable, SerializesModels;
    private Enquiry $contactUs;

    /**
     * Create a new message instance.
     * @param Enquiry $contactUs
     *
     */
    public function __construct(Enquiry $contactUs)
    {
        $this->contactUs = $contactUs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'contactUs' => $this->contactUs,
        ];

        return $this->subject('Contact Us - ' . settings('app_name'))
            ->to($this->contactUs->email, $this->contactUs->name)
            ->markdown('emails.inquiry.visitorFeedback', $data);
    }
}
