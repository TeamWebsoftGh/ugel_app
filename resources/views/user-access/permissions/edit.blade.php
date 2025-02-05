<form method="post" action="{{route('admin.permissions.store')}}" class="">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$permission->id}}">
    <input type="hidden" id="_name" name="me" value="{{$permission->display_name}}">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="confirm" class="col-form-label">Display Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required name="display_name" value="{{old('display_name', $permission->display_name)}}">
                <span class="input-note text-danger" id="error-display_name"> </span>
                @error('display_name')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="confirm" class="col-form-label">
                    Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" required name="name" value="{{old('name', $permission->name)}}">
                <span class="input-note text-danger" id="error-name"> </span>
                @error('name')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="confirm" class="col-form-label">Description</label>
                <input type="text" class="form-control" name="description" value="{{old('description', $permission->description)}}">
                <span class="input-note text-danger" id="error-description"> </span>
                @error('description')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
            <div class="form-group row">
                <div class="col-10">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>
    </div>

</form>
