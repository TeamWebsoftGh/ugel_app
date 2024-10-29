<?php

namespace App\Services\Helpers;

use Illuminate\Notifications\Notifiable;

class Response
{
    /**
     * The individual invoice items and their associated data.
     * @var array
     */
    public $message;
    public $status;


    /**
     * @var mixed
     */
    public $data;
}
