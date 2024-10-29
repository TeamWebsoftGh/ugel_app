<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
            'country_id' => $this->country_id,
            'country_name' => $this->country->name,
            'total_constituencies' => $this->constituencies->count(),
            'total_candidates' => $this->candidates->count(),
            'registered_voters' => $this->registered_voters,
            'total_voters' => $this->registered_voters,
            'updated_at' => $this->updated_at,
        ];
    }
}
