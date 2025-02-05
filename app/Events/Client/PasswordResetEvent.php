<?php
namespace App\Events\Client;

use App\Models\Client\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEvent
{

    // currently not in use 
    use Dispatchable, SerializesModels;

    public $user;

    public $newPassword;

    public function __construct(Client $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }
}
