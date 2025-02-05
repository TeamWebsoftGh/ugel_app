<form method="POST" action="{{route('admin.configurations.categories.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$category->id}}">
    <input type="hidden" id="_name" name="me" value="{{$category->name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label"> Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $category->name)}}" class="form-control">
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="code" class="control-label">Type <span class="text-danger">*</span></label>
                    <input type="text" name="type" class="form-control" value="{{old('type', $category->type)}}">
                    @error('type')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($category->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($category->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-8">
                    <label for="description" class="control-label">Description</label>
                    <input type="text" name="description" class="form-control" value="{{old('description', $category->description)}}">
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
