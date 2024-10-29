<?php

namespace App\Mail\Contract;

use App\Models\ContractAlert as ContractAlerts;
use Illuminate\Mail\Mailable;

class ContractAlert extends Mailable
{
    public $alert;

    /**
     * Create a new message instance.
     * @param ContractAlerts $alert
     * @return void
     */
    public function __construct(ContractAlerts $alert)
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
        $data = [
            'alert' => $this->alert,
        ];

        return $this->subject($this->alert->subject)
            ->markdown('emails.contract.alerts', $data);
    }
}
