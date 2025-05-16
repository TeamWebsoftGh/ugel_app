<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'reference'              => $this->reference,
            'note'                   => $this->note,
            'description'            => $this->description,
            'location'               => $this->location,
            'client_number'          => $this->client_number,
            'client_phone_number'    => $this->client_phone_number,
            'client_email'           => $this->client_email,
            'other_issue'            => $this->other_issue,
            'status'                 => $this->status,
            'is_notify'              => $this->is_notify,
            'completed_at'           => $this->completed_at,
            'closed_at'              => $this->closed_at,
            'client_name'            => $this->client->fullname,
            'client_id'              => $this->client_id,
            'property_name'          => $this->property->property_name,
            'property_id'            => $this->property_id,
            'property_unit_name'     => $this->propertyUnit->unit_name,
            'property_unit_id'       => $this->property_unit_id,
            'room_name'              => $this->room->room_name,
            'room_id'                => $this->room_id,
            'maintenance_category_name'   => $this->maintenanceCategory->name,
            'maintenance_category_id'     => $this->maintenance_category_id,
            'priority_name'          => $this->priority->name,
            'priority_id'            => $this->priority_id,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}
