<?php

namespace App\Mail;

use App\Models\Common\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public Email $alert;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['alert'] = $this->alert;
        return $this->subject($this->alert->subject)
            ->markdown('mail.alert', $data);
    }
}
