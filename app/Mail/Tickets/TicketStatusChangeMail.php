<?php

namespace App\Mail\Tickets;

use App\Models\CustomerService\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     * @param SupportTicket $ticket
     */
    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emails = $this->ticket->assignees()->pluck('email')->toArray();
        $data = [
            'ticket' => $this->ticket,
            'message' => "Ticket Status has changed to ".$this->ticket->status,
            'user' => $this->ticket->user,
            'url' => route("support-tickets.show", $this->ticket->id),
        ];


        if (count($emails) > 0){
            return $this->subject('Ticket Status Changed')
                ->markdown('emails.support-tickets.message', $data)->cc($emails);
        }

        return $this->subject('Ticket Status Changed')
            ->markdown('emails.support-tickets.message', $data);
    }
}
