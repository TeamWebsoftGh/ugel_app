<?php

namespace App\Listeners;

use App\Events\LeaveSubmittedEvent;
use App\Mail\Customer\BookingSubmittedMail;
use App\Mail\Customer\OrderSubmittedMail;
use App\Mail\Employees\LeaveSubmittedMail;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Admin\OrderSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LeaveSubmittedListener
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
     * @param LeaveSubmittedEvent $event
     * @return void
     */
    public function handle(LeaveSubmittedEvent $event)
    {
        $leave = $event->leave;

       try{
           // send email to customer
           send_mail(LeaveSubmittedMail::class, $leave, $leave->employee);

            $admins =  User::with('roles')->whereHas('roles', function($q){
                $q->where('name', "hr-admin");})
                ->get();

           //Notification to Admins
            notify_admins(OrderSubmitted::class, $event, $admins);
           // notify_admins(OrderSubmitted::class, $event, $admins);
       }catch (\Exception $ex){
           log_error(format_exception($ex), $leave, 'send-notification-failed');
       }
    }
}
