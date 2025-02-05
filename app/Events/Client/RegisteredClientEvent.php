<?php
namespace App\Events\Client;

use App\Models\Client\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisteredClientEvent
{

    // currently not in use 
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(Client $user)
    {
        $this->user = $user;
    }
}
