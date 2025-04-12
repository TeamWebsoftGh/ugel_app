<?php

namespace App\Listeners;

use App\Events\BookingEvent;
use App\Models\Common\Email;
use App\Models\Memo\SmsAlert;
use App\Traits\SmsTrait;
use Exception;

class BookingListener
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
     * @param BookingEvent $event
     * @return void
     */
    public function handle(BookingEvent $event)
    {
        $booking = $event->booking;
        $user = $booking->owner;
        $client = $booking->client;
        $property = $booking->propertyUnit->property ?? null;

        try {
            $emailData = [
                'to'             => $user->email,
                'cc'             => $client->email,
                'subject'        => 'Booking Successful',
                'message'        => "Your booking (Ref: {$booking->booking_number}) has been successfully received for property: {$property->property_name} ({$booking->propertyUnit->unit_name}).",
                'line_2'         => "Total Amount: " . format_money($booking->total_price),
                'line_3'         => "Check-in: " . $booking->lease_start_date,
                'line_4'         => "Check-out: " . $booking->lease_end_date,
                'is_sent'        => false,
                'request_date'   => now(),
                'emailable_type' => get_class($booking),
                'emailable_id'   => $booking->id,
                'eloquentable_type' => get_class($client),
                'eloquentable_id'   => $client->id ?? null,
                'company_id'     => company_id() ?? 1,
            ];

            if(isset($booking->room_id))
            {
                $emailData['line_1'] = "Room: " . $booking->room->room_name;
            }

            Email::create($emailData);

            if(settings('booking_sms', 1)){
                $smsData[] = [
                    'to' => $client->phone_number,
                    'sender_id' => settings('yoovi_sms_send_id'),
                    'message' => "Hi {$client->fullname}, your booking ({$booking->booking_number}) is received. Check-in: {$booking->lease_start_date}, Amount: " . format_money($booking->total_price),
                    'type' => "sms",
                    'company_id' => $booking->company_id,
                    'created_by' => $booking->created_by,
                    'is_sent' => 0,
                    'created_from' => 'web',
                ];

                SmsAlert::insert($smsData);
            }
        } catch (Exception $ex) {
            log_error(format_exception($ex), new Email(), "create-email-failed");
        }
    }

}
