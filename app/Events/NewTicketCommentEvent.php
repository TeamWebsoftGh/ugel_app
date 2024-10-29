<?php

namespace App\Events;

use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewTicketCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SupportTicketComment $comment;
    public SupportTicket $ticket;


    /**
     * Create a new event instance.
     *
     * @param SupportTicketComment $comment
     */
    public function __construct(SupportTicketComment $comment)
    {
        $this->comment  = $comment;
    }
}
