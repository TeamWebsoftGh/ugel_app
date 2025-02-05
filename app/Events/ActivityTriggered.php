<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActivityTriggered
{

    public $logAction;
    public $eloquent;
    public $description;

    /**
     * Create a new event instance.
     * @param string $description
     * @param $logAction
     * @param        $subject
     * @param null $division
     */
    public function __construct($description, $logAction, $subject = null)
    {
        $this->eloquent = $subject;
        $this->logAction = $logAction;
        $this->description = $description;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
