<form method="POST" action="{{route('presidential-candidates.store')}}" id="presidential_candidate" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$candidate->id}}">
    <input type="hidden" id="_name" name="me" value="{{$candidate->fullname}}">
    <div class="row clearfix">
        <div class="col-md-4">
            <div class="form-group">
                <label for="image" class="control-label">Image <span class="text-danger">*</span></label>
                <input type="file" name="image" id="image" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg jpeg" data-default-file="{{ asset("uploads/$candidate->image") }}" >
                <span class="input-note text-danger" id="error-image"> </span>
                @error('image')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label for="first_name" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{old('first_name', $candidate->first_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-first_name"> </span>
                    @error('first_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="other_names" class="control-label">Middle Name</label>
                    <input type="text" name="other_names" value="{{old('other_names', $candidate->other_names)}}" class="form-control">
                    <span class="input-note text-danger" id="error-other_names"> </span>
                    @error('other_names')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="last_name" class="control-label">Last Name</label>
                    <input type="text" name="last_name" value="{{old('last_name', $candidate->last_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-last_name"> </span>
                    @error('last_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Election  <span class="text-danger">*</span></label>
                    <select name="election_id" id="election_id" data-live-search="true" class="form-control selectpicker">
                        @forelse($elections as $election)
                            <option value="{{$election->id}}" @if(old('election_id', $candidate->election_id) == $election->id) selected @endif>{{$election->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-election_id"> </span>
                    @error('election_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Political Parties <span class="text-danger">*</span></label>
                    <select name="political_party_id" id="political_party_id" data-live-search="true" class="form-control selectpicker">
                        <option disabled selected>Nothing Selected</option>
                        @forelse($political_parties as $political_party)
                            <option value="{{$political_party->id}}" @if(old('political_party_id', $candidate->political_party_id) == $political_party->id) selected @endif>{{$political_party->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-political_party_id"> </span>
                    @error('political_party_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="1" @if($candidate->is_active == 1) selected="selected" @endif>Enable</option>
                        <option value="0" @if($candidate->is_active == 0) selected="selected" @endif>Disable</option>
                    </select>
                    <span class="input-note text-danger" id="error-status"> </span>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
<script>$('.dropify').dropify();</script>


