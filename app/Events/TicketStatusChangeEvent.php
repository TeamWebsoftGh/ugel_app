<?php

namespace App\Events;

use App\Models\SupportTicket;

class TicketStatusChangeEvent
{
    public SupportTicket $ticket;

    /**
     * Create a new event instance.
     * @param SupportTicket $ticket
     */
    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

}
