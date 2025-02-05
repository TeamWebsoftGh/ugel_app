<?php

namespace App\Events;


use App\Models\CustomerService\Enquiry;

class EnquiryFeedback
{
    public Enquiry $enquiry;
    /**
     * Create a new event instance.
     *
     * @param Enquiry $row
     */
    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
        log_activity('Enquiry', "{$this->enquiry->full_name} submitted a enquiry form.", $this->enquiry);
    }
}
