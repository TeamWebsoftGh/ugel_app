<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTaskEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $assignee;

    /**
     * Create a new event instance.
     *
     * @param Task $booking
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->assignee = $task->assignee;
    }
}
