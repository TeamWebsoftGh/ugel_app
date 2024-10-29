<?php

namespace App\Events;

use App\Models\ContactUs;

class ContactUsFeedback
{
    public $contactUs;
    /**
     * Create a new event instance.
     *
     * @param ContactUs $row
     */
    public function __construct(ContactUs $row)
    {
        $this->contactUs = $row;
        $row->type = 'Contact Us';
        $this->eloquent = $row;

        log_activity('Contact Us', "{$row->name} submitted a contact us form.", $row);
    }
}
