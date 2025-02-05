<?php

namespace App\Mail\Tickets;

use App\Models\CustomerService\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportTicket $ticket;

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
            'message' => "A new ticket has been created with subject ".$this->subject,
            'user' => $this->ticket->user,
            'url' => route("support-tickets.show", $this->ticket->id),
        ];


        if (count($emails) > 0){
            return $this->subject('New Ticket Created')
                ->markdown('emails.support-tickets.message', $data)->cc($emails);
        }

        return $this->subject('New Ticket Created')
            ->markdown('emails.support-tickets.message', $data);
    }
}
