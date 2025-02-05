<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ElectionResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'election_id' => 'required',
            'polling_station_id' => 'required',
            'polling_agent_id' => 'required',
            'presiding_officer_id' => 'required',
            'total_voters' => 'required|integer|min:0',
            'total_ballots' => 'required|integer|min:0',
            'total_voters_verified_by_bvd' => 'required|integer|min:0',
            'total_voters_verified_manually' => 'required|integer|min:0',
            'total_ballot_issued' => 'required|integer|min:0',
            'total_ballot_unused' => 'required|integer|min:0',
            'total_ballot_spoilt' => 'required|integer|min:0',
            'total_votes_in_box' => 'required|integer|min:0',
            'total_valid_votes' => 'required|integer|min:0|lte:total_voters',
            'candidate_votes.*.votes' => 'required|integer|min:0',
            'candidate_votes.*.candidate_id' => 'sometimes|integer|min:0',
        ];
    }
}
