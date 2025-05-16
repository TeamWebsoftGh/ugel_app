<?php

namespace App\Events;

use App\Models\Auth\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordChangeEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
