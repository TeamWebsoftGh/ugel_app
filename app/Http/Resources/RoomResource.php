<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->room_name,
            'property_unit_name' => $this->propertyUnit->unit_name,
            'property_unit_id' => $this->propertyUnit->id,
            'property_name' => $this->propertyUnit->property->property_name,
            'property_id' => $this->propertyUnit->property->id,
            'rent_amount' => $this->propertyUnit->rent_amount,
            'rent_type' => $this->propertyUnit->rent_type,
            'bed_count' => $this->bed_count,
            'floor' => $this->floor,
            'has_ac' => $this->has_ac,
            'has_washroom' => $this->has_washroom,
            'is_active' => $this->is_active,
            'status' => $this->is_active,
            'description' => $this->status,
        ];
    }
}
