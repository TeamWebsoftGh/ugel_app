<?php

namespace App\Mail\Visitor;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMailToContactUsSender extends Mailable
{
    use Queueable, SerializesModels;
    private $contactUs;

    /**
     * Create a new message instance.
     * @param ContactUs $contactUs
     *
     * @return void
     */
    public function __construct(ContactUs $contactUs)
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
