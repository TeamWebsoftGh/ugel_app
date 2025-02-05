<form method="POST" action="{{route('polling-stations.store')}}" id="polling_station" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$polling_station->id}}">
    <input type="hidden" id="_name" name="me" value="{{$polling_station->ame}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $polling_station->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="code" class="control-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{old('code', $polling_station->code)}}" class="form-control">
                    <span class="input-note text-danger" id="error-code"> </span>
                    @error('code')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Constituency <span class="text-danger">*</span></label>
                    <select name="constituency_id" id="constituency_id" data-live-search="true" class="form-control selectpicker">
                        @forelse($constituencies as $constituency)
                            <option value="{{$constituency->id}}" @if(old('constituency_id', $polling_station->electoral_area?->constituency_id) == $constituency->id) selected @endif>{{$constituency->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-constituency_id"> </span>
                    @error('constituency_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Electoral Area <span class="text-danger">*</span></label>
                    <select name="electoral_area_id" id="electoral_area_id" data-live-search="true" class="form-control selectpicker">
                        <option disabled selected>Nothing Selected</option>
                        @forelse($electoral_areas as $electoral_area)
                            <option value="{{$electoral_area->id}}" @if(old('electoral_area_id', $polling_station->electoral_area_id) == $electoral_area->id) selected @endif>{{$electoral_area->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-electoral_area_id"> </span>
                    @error('electoral_area_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="1" @if($polling_station->is_active == 1) selected="selected" @endif>Enable</option>
                        <option value="0" @if($polling_station->is_active == 0) selected="selected" @endif>Disable</option>
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

