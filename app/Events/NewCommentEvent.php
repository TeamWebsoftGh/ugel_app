<?php

namespace App\Events;

use App\Models\TaskComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $task;


    /**
     * Create a new event instance.
     *
     * @param TaskComment $comment
     */
    public function __construct(TaskComment $comment)
    {
        $this->comment  = $comment;
        $this->task  = $comment->task;
    }
}
