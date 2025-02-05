<form method="POST" action="{{route('admin.customer-types.store')}}" id="client_type" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$client_type->id}}">
    <input type="hidden" id="_name" name="me" value="{{$client_type->name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label"> Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $client_type->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="code" class="control-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{old('code', $client_type->code)}}" class="form-control">
                    <span class="input-note text-danger" id="error-code"> </span>
                    @error('code')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label class="control-label">Category <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker" data-live-search="true" data-msg="Required" id="category" name="category">
                        <option value="individual" @if($client_type->category == "individual") selected="selected" @endif>Individual</option>
                        <option value="business" @if($client_type->category == "business") selected="selected" @endif>Business</option>
                    </select>
                    <span class="input-note text-danger" id="error-category"> </span>
                    @error('category')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label> Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($client_type->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($client_type->is_active == 1) selected="selected" @endif>Enable</option>
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
