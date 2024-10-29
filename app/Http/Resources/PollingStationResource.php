<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollingStationResource extends JsonResource
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
            'name' => $this->name,
            'is_active' => $this->is_active,
            'code' => $this->code,
            'electoral_area_id' => $this->electoral_area_id,
            'electoral_area_name' => $this->electoral_area->name,
            'electoral_area_code' => $this->electoral_area->code,
            'updated_at' => $this->updated_at,
            'total_voters' => $this->delegates()->count(),
            'registered_voters' => $this->registered_voters,
        ];
    }
}
