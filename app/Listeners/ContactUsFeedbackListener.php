<?php

namespace App\Listeners;

use App\Events\EnquiryFeedback;
use App\Mail\Visitor\sendMailToContactUsSender;
use App\Notifications\ContactUsSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ContactUsFeedbackListener
{
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
     * @param  EnquiryFeedback  $event
     * @return void
     */
    public function handle(EnquiryFeedback $event)
    {
        // send email to customer
        Mail::send(new sendMailToContactUsSender($event->contactUs));

        //notify_admins(ContactUsSubmitted::class, $event->contactUs);
    }
}
