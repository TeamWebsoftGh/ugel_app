<form method="POST" action="{{route('resource.categories.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$category->id}}">
    <input type="hidden" id="_name" name="me" value="{{$category->name}}">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $category->name)}}" class="form-control">
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="status" class="form-control selectpicker">
                        <option value="0">Disable</option>
                        <option value="1">Enable</option>
                    </select>
                    @error('status')
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
<script>
    $('#status').selectpicker('val', '{{$category->is_active}}');
</script>
