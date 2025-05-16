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
    public function __construct($data)
    {
        $this->alert = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['alert'] = $this->alert;
        $email = $this->subject($this->alert->subject)->view('emails.alert', $data);

        // Check if CC is set and not empty
        if (isset($this->alert->cc) && !empty($this->alert->cc)) {
            // Assuming $this->data->cc is a string of email addresses separated by commas
            $ccEmails = explode(',', $this->alert->cc);
            // Add CC emails to the email
            $email = $email->cc($ccEmails);
        }

        // Check if BCC is set and not empty
        if (isset($this->alert->bcc) && !empty($this->alert->bcc)) {
            // Assuming $this->data->bcc is a string of email addresses separated by commas
            $bccEmails = explode(',', $this->alert->bcc);
            // Add BCC emails to the email
            $email = $email->bcc($bccEmails);
        }

        return $email;
    }
}
