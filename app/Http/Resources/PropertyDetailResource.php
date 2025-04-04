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
        $unitPrices = $this->propertyUnits->pluck('rent_amount')->filter();

        $minPrice = $unitPrices->min();
        $maxPrice = $unitPrices->max();
        return [
            'id' => $this->id,
            'property_code' => $this->property_code,
            'property_name' => $this->property_name,
            'starting_price' => format_money($this->propertyUnits->min('rent_amount') ?? 0),
            'price_range' => $minPrice && $maxPrice
                ? format_money($minPrice) . ' - ' . format_money($maxPrice)
                : null,
            'image' => asset($this->image),
            'icon' => $this->icon,
            'number_of_units' => count($this->propertyUnits),
            'status' => $this->status,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'property_type_name' => $this->propertyType?->name,
            'property_type_id' => $this->propertyType?->id,
            'property_purpose_id' => $this->propertyType?->id,
            'property_purpose_name' => $this->propertyPurpose?->name,
            'property_type' => new PropertyTypeResource($this->propertyType),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'property_units' => PropertyUnitResource::collection($this->whenLoaded('propertyUnits')),
        ];
    }
}
