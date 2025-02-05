<?php

namespace App\Services\Helpers;

use Illuminate\Notifications\Notifiable;

class PayDeductionResponse
{
    /**
     * The individual invoice items and their associated data.
     * @var array
     */
    public $employer;
    public $employee;


    /**
     * @var mixed
     */
    public $total;
    public $tier1;
    public $tier2;

}
