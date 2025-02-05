<form method="POST" action="{{ route('election-results.store') }}" id="election_result" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $election_result->id }}">

    <!-- Additional fields -->
    <div class="row clearfix">
        <!-- Additional fields as specified -->
        <div class="form-group col-6 col-md-4">
            <label>Election  <span class="text-danger">*</span></label>
            <select name="election_id" id="election_id" data-live-search="true" class="form-control selectpicker">
                @forelse($elections as $election)
                    <option value="{{$election->id}}" @if(old('election_id', $election_result->election_id) == $election->id) selected @endif>{{$election->name}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-election_id"> </span>
            @error('election_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>Polling Station <span class="text-danger">*</span></label>
            <select name="polling_station_id" id="polling_station_id" data-live-search="true" class="form-control selectpicker">
                @forelse($polling_stations as $polling_station)
                    <option value="{{$polling_station->id}}" @if(old('polling_station_id', $election_result->polling_station_id) == $polling_station->id) selected @endif>{{$polling_station->name}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-polling_station_id"> </span>
            @error('polling_station_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="primary_bvd_serial_number" class="control-label">Primary BVD Serial Number</label>
            <input type="text" name="primary_bvd_serial_number" value="{{ old('primary_bvd_serial_number', $election_result->primary_bvd_serial_number ?? '') }}" class="form-control">
            <span class="input-note text-danger" id="error-primary_bvd_serial_number"> </span>
            @error('primary_bvd_serial_number')
            <span class="input-note text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="secondary_bvd_serial_number" class="control-label">Secondary BVD Serial Number</label>
            <input type="text" name="secondary_bvd_serial_number" value="{{ old('secondary_bvd_serial_number', $election_result->secondary_bvd_serial_number ?? '') }}" class="form-control">
            <span class="input-note text-danger" id="error-secondary_bvd_serial_number"> </span>
            @error('secondary_bvd_serial_number')
            <span class="input-note text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="validation_stamp_serial_number" class="control-label">Validation Stamp Serial Number</label>
            <input type="text" name="validation_stamp_serial_number" value="{{ old('validation_stamp_serial_number', $election_result->validation_stamp_serial_number ?? '') }}" class="form-control">
            <span class="input-note text-danger" id="error-validation_stamp_serial_number"> </span>
            @error('validation_stamp_serial_number')
            <span class="input-note text-danger">{{ $message }}</span>
            @enderror
        </div>
        @php
            $fields = [
                'total_voters' => 'Total Voters',
                'total_ballots' => 'Total Ballots',
                'total_voters_verified_by_bvd' => 'Total Voters Verified by BVD',
                'total_voters_verified_manually' => 'Total Voters Verified Manually',
                'total_ballot_issued' => 'Total Ballot Issued',
                'total_ballot_unused' => 'Total Ballot Unused',
                'total_ballot_spoilt' => 'Total Ballot Spoilt',
                'total_votes_in_box' => 'Total Votes in Box',
                'total_valid_votes' => 'Total Valid Votes'
            ];
        @endphp

        @foreach ($fields as $key => $label)
            <div class="form-group col-md-4">
                <label for="{{ $key }}" class="control-label">{{ $label }}</label>
                <input type="number" name="{{ $key }}" value="{{ old($key, $election_result->$key ?? '') }}" class="form-control">
                <span class="input-note text-danger" id="error-{{$key}}"> </span>
                @error($key)
                <span class="input-note text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
        <div class="form-group col-6 col-md-4">
            <label>Polling Agent  <span class="text-danger">*</span></label>
            <select name="polling_agent_id" id="polling_agent_id" data-live-search="true" class="form-control selectpicker">
                @forelse($users as $usr)
                    <option value="{{$usr->id}}" @if(old('polling_agent_id', $election_result->polling_agent_id) == $usr->id) selected @endif>{{$usr->fullname}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-polling_agent_id"> </span>
            @error('polling_agent_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>Presiding Officer  </label>
            <select name="presiding_officer_id" id="presiding_officer_id" data-live-search="true" class="form-control selectpicker">
                @forelse($users as $usr)
                    <option value="{{$usr->id}}" @if(old('presiding_officer_id', $election_result->presiding_officer_id) == $usr->id) selected @endif>{{$usr->fullname}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-presiding_officer_id"> </span>
            @error('presiding_officer_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        @include("shared.form.attachment", ['attachment' => $election_result->attachment])
    </div>
    <br/>
    <br/>
    <!-- Candidate specific voting fields -->
    <div class="row clearfix">
        <div class="col-12">
            <h5 class="title">Candidates Details:</h5>
        </div>
        @forelse($candidates as $candidate)
            @php
                $result = $election_result->candidates->firstWhere('candidate_id', $candidate->id);
            @endphp
            <div class="col-md-12 mt-3">
                <div class="row">
                    <div class="form-group col-md-6">
                        <h5><img src="{{ asset("$candidate->UserImage") }}" class="avatar-sm rounded-circle me-2"/>{{ $candidate->fullname }} ({{ $candidate->political_party->code }})</h5>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="number" name="candidate_votes[{{ $candidate->id }}][votes]" class="form-control" value="{{ old('candidate_votes.'.$candidate->id.'.votes', $result ? $result->votes : 0) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" id="percentage_{{ $candidate->id }}" disabled value="{{$result ? $result->percentage : 0}}%" class="form-control" placeholder="Enter percentage">
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>
    <div class="form-group col-12">
        @include("shared.save-button")
    </div>
</form>





