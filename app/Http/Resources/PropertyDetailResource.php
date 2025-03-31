<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyDetailResource extends JsonResource
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
            'property_code' => $this->property_code,
            'property_name' => $this->property_name,
            'image' => asset($this->image),
            'icon' => $this->icon,
            'number_of_units' => $this->number_of_units,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'property_type_name' => $this->propertyType?->name,
            'property_type_id' => $this->propertyType?->id,
            'property_purpose_id' => $this->propertyType?->id,
            'property_purpose_name' => $this->propertyPurpose?->name,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
