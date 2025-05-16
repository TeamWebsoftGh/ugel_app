<?php

namespace App\Notifications\CustomerService;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SupportTicketNotification extends Notification
{
    use Queueable;

    protected $supportTicket;

    public function __construct($supportTicket)
    {
        $this->supportTicket = $supportTicket;
    }

    public function via($notifiable)
    {
        return ['database']; // You can also add 'broadcast' if needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Maintenance Request Created')
            ->line('A new maintenance request has been created.')
            ->line('Reference: ' . $this->supportTicket->reference)
            ->action('View Request', url(route('maintenance-requests.show', $this->supportTicket->id)))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'maintenance_id' => $this->supportTicket->id,
            'reference'      => $this->supportTicket->reference,
            'message'        => 'A new support ticket with reference ' . $this->supportTicket->reference . ' has been created by ' . $this->supportTicket->owner->fullname??'user',
            'url'            => route('support-tickets.show', $this->supportTicket->id),
            'type'           => get_class($this->supportTicket),
            'icon'           => 'check',
            'title'          => 'New Support Ticket Created',
        ];
    }

}
