<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'category_id' => $this->property_category->id,
            'category' => $this->property_category->name,
            'updated_at' => $this->updated_at,
        ];
    }
}
