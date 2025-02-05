<form method="POST" action="{{route('elections.store')}}" id="election" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$election->id}}">
    <input type="hidden" id="_name" name="me" value="{{$election->ame}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $election->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control selectpicker">
                        <option value="parliamentary" @if($election->type == 'parliamentary') selected="selected" @endif>Parliamentary</option>
                        <option value="presidential" @if($election->type == 'presidential') selected="selected" @endif>Presidential</option>
                    </select>
                    <span class="input-note text-danger" id="error-type"> </span>
                    @error('type')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="election_date" class="control-label">Election Date <span class="text-danger">*</span></label>
                    <input type="text" name="election_date" value="{{old('election_date', $election->election_date)}}" class="form-control date">
                    <span class="input-note text-danger" id="error-election_date"> </span>
                    @error('election_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($election->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($election->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
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

