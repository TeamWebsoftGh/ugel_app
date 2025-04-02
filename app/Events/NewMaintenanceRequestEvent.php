<?php

namespace App\Events;

use App\Models\CustomerService\MaintenanceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMaintenanceRequestEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $maintenanceRequest;

    /**
     * Create a new event instance.
     *
     * @param MaintenanceRequest $maintenanceRequest
     */
    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }
}
