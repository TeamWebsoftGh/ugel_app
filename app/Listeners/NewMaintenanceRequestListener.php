<?php

namespace App\Listeners;

use App\Events\NewClientEvent;
use App\Events\NewMaintenanceRequestEvent;
use App\Models\Common\Email;
use App\Models\Communication\SmsAlert;
use App\Notifications\CustomerService\MaintenanceRequestNotification;
use App\Traits\SmsTrait;
use Exception;
use Illuminate\Support\Facades\Notification;

class NewMaintenanceRequestListener
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
    public function handle(NewMaintenanceRequestEvent $event)
    {
        $maintenanceRequest = $event->maintenanceRequest;

        // send email to job applicant
        try {

            $maintenance = $maintenanceRequest;
            $client = optional($maintenance->client)->email ?? 'default@example.com';

            $emailData = [
                'to'             => $client->email,
                'subject'        => 'New Maintenance Request Created',
                'message'        => 'Your maintenance request has been created successfully. Reference: ' . $maintenance->reference,
                'line_1'         => 'Maintenance Request Created',
                'line_2'         => 'We are working on your request and will update you soon.',
                'line_3' => "If you have any questions or need assistance, please don't hesitate to reach out to our support team.",
                'is_sent'        => false,
                'request_date'   => now(),
                'emailable_type' => get_class($maintenance),
                'emailable_id'   => $maintenance->id,
                'button_url'   => route('maintenance-requests.show', $maintenance->id),
                'button_name'   => "View Maintenance Request",
                'eloquentable_type'   => get_class($client),
                'eloquentable_id'   => $client?->id,
                'company_id'   => company_id()??1,
            ];

            if(settings('maintenance_sms', 1)){
                $smsData[] = [
                    'to' => $maintenance->client->phone_number,
                    'sender_id' => settings('yoovi_sms_send_id'),
                    'message' => "New Maintenance Request Created. Reference: " . $maintenance->reference,
                    'type' => "sms",
                    'company_id' => $maintenance->company_id,
                    'created_by' => $maintenance->created_by,
                    'is_sent' => 0,
                    'created_from' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                SmsAlert::insert($smsData);
            }

            // Send notifications to the maintenance assignees if available.
            if ($maintenance->assignees()->exists()) {
                Notification::send($maintenance->assignees, new MaintenanceRequestNotification($maintenance));
            }

            return Email::create($emailData);
        }catch (Exception $ex){
            log_error(format_exception($ex), new Email(), "create-email-failed");
        }
    }
}
