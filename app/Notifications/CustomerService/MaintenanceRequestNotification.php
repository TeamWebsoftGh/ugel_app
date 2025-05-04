<?php

namespace App\Notifications\CustomerService;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MaintenanceRequestNotification extends Notification
{
    use Queueable;

    protected $maintenance;

    public function __construct($maintenance)
    {
        $this->maintenance = $maintenance;
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
            ->line('Reference: ' . $this->maintenance->reference)
            ->action('View Request', url(route('maintenance-requests.show', $this->maintenance->id)))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'reference'      => $this->maintenance->reference,
            'message'        => 'A new maintenance request with reference ' . $this->maintenance->reference . ' has been created.',
            'url'            => route('maintenance-requests.show', $this->maintenance->id),
            'type'           => get_class($this->maintenance),
            'icon'           => 'check',
            'title'          => 'Maintenance request submitted by '.$this->maintenance?->client?->fullname??"user",
        ];
    }

}
