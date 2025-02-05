<form method="POST" action="{{route('organization.subsidiaries.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$company->id}}">
    <input type="hidden" id="_name" name="me" value="{{$company->company_name}}">
    <div class="row clearfix">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" value="{{old('name', $company->company_name)}}" class="form-control">
                    @error('company_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="email_address" class="control-label">Email Address </label>
                    <input type="email" name="email_address" value="{{old('email_address', $company->email_address)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email_address"> </span>
                    @error('email_address')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Phone Number </label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $company->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Fax Number</label>
                    <input type="tel" name="fax_number" value="{{old('fax_number', $company->fax_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-fax_number"> </span>
                    @error('fax_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($company->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($company->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-8">
                    <label for="description" class="control-label">Description </label>
                    <textarea class="form-control" rows="6" name="description">{{old('', $company->description)}}</textarea>
                    <span class="input-note text-danger" id="error-description"> </span>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
    </div>
</form>

