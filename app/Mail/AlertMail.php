<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public object $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['data'] = $this->data->toArray();
        $email = $this->subject($this->data->subject)->view('emails.alert', $data);

        // Check if CC is set and not empty
        if (isset($this->data->cc) && !empty($this->data->cc)) {
            // Assuming $this->data->cc is a string of email addresses separated by commas
            $ccEmails = explode(',', $this->data->cc);
            // Add CC emails to the email
            $email = $email->cc($ccEmails);
        }

        // Check if BCC is set and not empty
        if (isset($this->data->bcc) && !empty($this->data->bcc)) {
            // Assuming $this->data->bcc is a string of email addresses separated by commas
            $bccEmails = explode(',', $this->data->bcc);
            // Add BCC emails to the email
            $email = $email->bcc($bccEmails);
        }

        return $email;
    }
}
