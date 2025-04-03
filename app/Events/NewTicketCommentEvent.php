<?php

namespace App\Events;

use App\Models\Common\Comment;
use App\Models\CustomerService\SupportTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewTicketCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Comment $comment;
    public SupportTicket $ticket;


    /**
     * Create a new event instance.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment  = $comment;
    }
}
