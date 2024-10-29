<?php

namespace App\Listeners;


use App\Models\Audit\LogAction;
use App\Models\Audit\LogActivity;

class LogActivityListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // log adjustment
        $user = user();
        $logAction = LogAction::firstOrCreate(['slug' => $event->logAction]);
        $log = new LogActivity();


        $log->description = $event->description;
        if (strlen( $log->description) < 2) {
            $log->description = str_replace(['App\\', 'Models\\'], '', get_class($event->eloquent));
        }

        $subject = null;
        if (!is_null($event->eloquent)) {
            $subject = get_class($event->eloquent);
        }

        $log->subject_id = $event->eloquent ? $event->eloquent->id : null;

        if ($event->eloquent && !strpos($subject, '\Models')) {
            $log->subject_id = null;
            $subject = 'App\Models\Auth\User';
        }

        $log->subject_type = $subject;
        $log->client_ip = request()->getClientIp();
        $log->client_agent = request()->userAgent();
        $log->request_url = request()->url();
        $log->log_type_Id = $logAction->logType->id;
        $log->log_action_id = $logAction->id;
        $log->company_id = company_id()??1;

        if ($user){
            $log->user_id = $user->id;
            $log->user_model = get_class($user);
        }

        $log->save();
    }
}
