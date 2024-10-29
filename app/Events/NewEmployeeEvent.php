<?php

namespace App\Events;


use App\Mail\Admin\AccountCreatedMail;
use App\Models\Auth\User;

class NewEmployeeEvent
{
    public User $user;

    /**
     * Create a new event instance.
     *
     * @param User $row
     */
    public function __construct(User $row)
    {
        $this->user = $row;

        if (settings("send_mail_new_account", 1)){
            send_mail(AccountCreatedMail::class, $this->user, $this->user);
        }

        log_activity('Contact Us', "{$row->name} submitted a contact us form.", $row);
    }
}
