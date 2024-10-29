<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElectionResultResource extends JsonResource
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
            'election_id' => $this->election_id,
            'election_name' => $this->election->name,
            'polling_station_id' => $this->polling_station_id,
            'polling_station_name' => $this->polling_station_name,
            'polling_station_code' => $this->polling_station_code,
            'primary_bvd_serial_number' => $this->primary_bvd_serial_number,
            'secondary_bvd_serial_number' => $this->secondary_bvd_serial_number,
            'validation_stamp_serial_number' => $this->validation_stamp_serial_number,
            'total_voters' => $this->total_voters,
            'total_ballots' => $this->total_ballots,
            'total_voters_verified_by_bvd' => $this->total_voters_verified_by_bvd,
            'total_voters_verified_manually' => $this->total_voters_verified_manually,
            'total_ballot_issued' => $this->total_ballot_issued,
            'total_ballot_unused' => $this->total_ballot_unused,
            'total_ballot_spoilt' => $this->total_ballot_spoilt,
            'total_votes_in_box' => $this->total_votes_in_box,
            'total_valid_votes' => $this->total_valid_votes,
            'candidates' => CandidateElectionResultResource::collection($this->candidates),
            'polling_agent_id' => $this->polling_agent_id,
            'polling_agent_name' => $this->polling_agent?->fullname,
            'presiding_officer_id' => $this->presiding_officer_id,
            'presiding_officer_name' => $this->presiding_officer?->fullname,
            'documents' => DocumentUploadResource::collection($this->whenLoaded('documents')),
            'attachment' => new DocumentUploadResource($this->whenLoaded('attachment')),
            'updated_at' => $this->updated_at,
        ];
    }
}
