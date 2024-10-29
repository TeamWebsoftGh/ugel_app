<form method="POST" action="{{route('admin.roles.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$role->id}}">
    <input type="hidden" id="_name" name="me" value="{{$role->display_name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $role->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="display_name" class="control-label">Display Name <span class="text-danger">*</span></label>
                    <input type="text" name="display_name" value="{{old('display_name', $role->display_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-display_name"> </span>
                    @error('display_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($role->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($role->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-8">
                    <label for="description" class="control-label">Description </label>
                    <textarea class="form-control" rows="3" name="description">{{old('', $role->description)}}</textarea>
                    <span class="input-note text-danger" id="error-description"> </span>
                    @error('description')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-12">
                    @include("user-access.permissions.permissions-inline")
                    @error('permissions')
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
