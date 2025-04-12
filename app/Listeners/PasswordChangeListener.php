<?php

namespace App\Listeners;

use App\Events\PasswordChangeEvent;
use App\Models\Common\Email;
use App\Traits\SmsTrait;
use Exception;

class PasswordChangeListener
{
    use SmsTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PasswordChangeEvent $event
     * @return void
     */
    public function handle(PasswordChangeEvent $event)
    {
        $user = $event->user;

        // send email to job applicant
        try {

            $emailData = [
                'to'             => $user->email,
                'subject'        => 'Password Changed',
                'message'        => 'The password linked to your account has been successfully updated.',
                'line_5'         => "Don’t recognize this activity? Please reset your password and contact customer support immediately.",
                'is_sent'        => false,
                'request_date'   => now(),
                'emailable_type' => get_class($user),
                'emailable_id'   => $user->id,
                'button_url'   => route('login'),
                'button_name'   => "Log in to your account",
                'eloquentable_type'   => get_class($user),
                'eloquentable_id'   => $user?->id,
                'company_id'   => company_id()??1,
            ];

            return Email::create($emailData);
        }catch (Exception $ex){
            log_error(format_exception($ex), new Email(), "create-email-failed");
        }
    }
}
