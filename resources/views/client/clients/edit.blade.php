<form method="POST" action="{{route('admin.customers.store')}}" id="users" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$client->id}}">
    <input type="hidden" id="_name" name="me" value="{{$client->fullname}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-6">
                    <label class="control-label">Client Type <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker" data-live-search="true" data-msg="Required" id="client_type_id" name="client_type_id">
                        @forelse($client_types as $type)
                            <option value="{{$type->id}}" data-category="{{$type->category}}" @if(old('role', $client->client_type_id) == $type->id) selected="selected" @endif>{{$type->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-client_type_id"> </span>
                    @error('client_type_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{old('first_name', $client->first_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-first_name"> </span>
                    @error('first_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="last_name" class="control-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" value="{{old('last_name', $client->last_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-last_name"> </span>
                    @error('last_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="other_names" class="control-label">Other Names </label>
                    <input type="text" name="other_names" value="{{old('other_names', $client->other_names)}}" class="form-control">
                    <span class="input-note text-danger" id="error-other_names"> </span>
                    @error('other_names')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{old('email', $client->email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email"> </span>
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="client_number" class="control-label">Client/Student Number <span class="text-danger">*</span></label>
                    <input type="text" id="client_number" @if($client->client_number != null) readonly @endif name="client_number" class="form-control" value="{{old('client_number', $client->client_number)}}">
                    <span class="input-note text-danger" id="error-client_number"> </span>
                    @error('client_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">Phone Number</label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $client->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
            </div>

            <div class="row" id="businessFields">
                <div class="col-md-12">
                    <h5>Business Details</h5>
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="business_name" class="control-label">Business Name <span class="text-danger">*</span></label>
                    <input type="text" name="business_name" value="{{old('business_name', $client->business_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-business_name"> </span>
                    @error('business_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="business_telephone" class="control-label">Business Phone <span class="text-danger">*</span></label>
                    <input type="text" name="business_telephone" value="{{old('business_telephone', $client->business_telephone)}}" class="form-control">
                    <span class="input-note text-danger" id="error-business_telephone"> </span>
                    @error('business_telephone')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="business_email" class="control-label">Business Email <span class="text-danger">*</span></label>
                    <input type="email" name="business_email" value="{{old('business_email', $client->business_email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-business_email"> </span>
                    @error('business_email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="physical_address" class="control-label">Address</label>
                    <input type="text" name="physical_address" value="{{old('physical_address', $client->physical_address)}}" class="form-control">
                    <span class="input-note text-danger" id="error-physical_address"> </span>
                    @error('physical_address')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6 col-md-6">
                    <label for="comment" class="control-label">Comment </label>
                    <input type="text" name="comment" value="{{old('comment', $client->comment)}}" class="form-control">
                    <span class="input-note text-danger" id="error-comment"> </span>
                    @error('comment')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-6">
                    <label> Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($client->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($client->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                @if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
                    <button type="submit" class="btn btn-success hide_show"><i class="fa fa-save"></i> Save</button>
                @endif
            </div>
        </div>
    </div>
</form>

