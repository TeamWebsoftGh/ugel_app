<?php

namespace App\Listeners;

use App\Events\TerminationEvent;
use App\Mail\Writer\AccountCreatedMail;
use App\Models\User;
use App\Notifications\Admin\NewWriterRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriterRegistrationListener
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
     * @param TerminationEvent $event
     * @return void
     */
    public function handle(TerminationEvent $event)
    {
        $writer = $event->writer;

        try{
            // send email to job applicant
            send_mail(AccountCreatedMail::class, $writer, $writer);

            //Notify Admins to review and approve writer
            if($writer->account_verified_at == null){
                $admins =  User::with('roles')->whereHas('roles', function($q){
                    $q->where('name', "admin");})
                    ->get()
                    ->except(array(1));

                $admins = $admins->where('status', 1)->take(2);

                //Notification to Admins
                notify_admins(NewWriterRegistration::class, $event, $admins);
                // notify_admins(OrderSubmitted::class, $event, $admins);
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $writer, 'send-notification-failed');
        }
    }
}
