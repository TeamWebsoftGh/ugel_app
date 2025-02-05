<?php

namespace App\Events;

use App\Models\Client\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewClientEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Client $client;

    /**
     * Create a new event instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
