<?php

namespace App\Mail\Admin;

use App\Traits\SmsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels, SmsTrait;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->phone_number)
        {
            $body = "Hello! Username: ".$this->user->username.", Temporary Password: ".$this->user->password.". Login to change it at ".route('login').".";
            $this->sendSms($this->user->phone_number, $body);
        }

        $subject = "APP CREDENTIALS";
        $data = [
            'user' => $this->user,
            'url' => route('login'),
        ];
        return $this->subject($subject)
            ->markdown('emails.admin.register', $data);
    }
}
