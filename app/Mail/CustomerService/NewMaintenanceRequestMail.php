<?php

namespace App\Mail\CustomerService;

use App\Models\CustomerService\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMaintenanceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public MaintenanceRequest $maintenanceRequest;

    /**
     * Create a new message instance.
     * @param MaintenanceRequest $maintenanceRequest
     */
    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emails = $this->maintenanceRequest->assignees()->pluck('email')->toArray();
        $data = [
            'ticket' => $this->maintenanceRequest,
            'message' => "A new maintenance request has been created.",
            'user' => $this->maintenanceRequest->user,
            'url' => route("maintenance-requests.show", $this->maintenanceRequest->id),
        ];


        if (count($emails) > 0){
            return $this->subject('New Ticket Created')
                ->markdown('emails.support-tickets.message', $data)->cc($emails);
        }

        return $this->subject('New Ticket Created')
            ->markdown('emails.support-tickets.message', $data);
    }
}
