<?php

namespace App\Events;

use App\Models\Loan\Loan;

class UpdateTransactionEvent
{
    public Loan $loan;
    /**
     * Create a new event instance.
     *
     * @param Loan $row
     */
    public function __construct(Loan $row)
    {
        $this->loan = $row;
    }
}
