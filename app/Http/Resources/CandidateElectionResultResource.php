<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateElectionResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'candidate_id' => $this->candidate_id,
            'candidate_name' => $this->candidate?->fullname,
            'candidate_photo' => asset($this->candidate?->UserImage),
            'votes' => $this->votes,
            'percentage' => $this->percentage,
            'party_name' => $this->candidate?->political_party?->name,
            'party_code' => $this->candidate->political_party->code,
            'party_image' => asset($this->candidate->political_party->photo),
        ];
    }
}
