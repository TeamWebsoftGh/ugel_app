<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyUnitResource extends JsonResource
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
            'name' => $this->unit_name,
            'property_name' => $this->property->property_name,
            'image' => isset($this->image)?asset($this->image):null,
            'rent_amount' => $this->rent_amount,
            'rent_type' => $this->rent_type,
            'icon' => $this->icon,
            'status' => $this->status,
            'total_bedroom' => $this->total_bedroom,
            'total_bathroom' => $this->total_bathroom,
            'total_kitchen' => $this->total_kitchen,
            'total_rooms' => $this->total_rooms,
            'parking' => $this->parking,
            'condition' => $this->condition,
            'unit_floor' => $this->unit_floor,
            'square_foot' => $this->square_foot,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'property_type_name' => $this->property->propertyType?->name,
            'property_type_id' => $this->property->propertyType?->id,
            'property_purpose_id' => $this->property->property_purpose_id,
            'property_purpose_name' => $this->property->propertyPurpose?->name,
        ];
    }
}
