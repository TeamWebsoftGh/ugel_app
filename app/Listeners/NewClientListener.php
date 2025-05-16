<?php

namespace App\Listeners;

use App\Events\NewClientEvent;
use App\Models\Common\Email;
use App\Traits\SmsTrait;
use Exception;


class NewClientListener
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
     * @param NewClientEvent $event
     * @return void
     */
    public function handle(NewClientEvent $event)
    {
        $client = $event->client;

        // send email to job applicant
        try {
            $companyName = settings("company_name");
            $data = new Email();

            $data['eloquentable_type'] = get_class($client);
            $data['eloquentable_id'] = $client ? optional($client)->id : null;;
            $data['line_1'] = "Welcome to {$companyName}! We're excited to have you onboard.";
            $data['line_2'] = "You can now manage your bookings, track rent payments, request maintenance, and more – all from your dashboard.";
            $data['line_3'] = "If you have any questions or need assistance, please don't hesitate to reach out to our support team.";
            $data['emailable_id'] = $client->id;
            $data['emailable_type'] = get_class($client);
            $data['to'] = $client->email;
//            $data['button_url'] = route('client.login');
//            $data['button_name'] = "Login";
            $data['subject'] = "Welcome to ".settings("company_name");
            $data['company_id'] = company_id()??1;

            return Email::create($data->toArray());
        }catch (Exception $ex){
            log_error(format_exception($ex), new Email(), "create-email-failed");
        }
    }
}
