<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParliamentaryCandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'candidate_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_names' => $this->other_names,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'photo' => asset($this->UserImage),
            'constituency_id' => $this->constituency_id,
            'constituency_name' => $this->constituency->name,
            'political_party_id' => $this->political_party_id,
            'political_party_code' => $this->political_party->code,
            'election_id' => $this->election_id,
            'election_name' => $this->election->name
        ];
    }
}
