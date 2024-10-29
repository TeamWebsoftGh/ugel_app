<form method="POST" action="{{route('resource.resources.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$resource->id}}">
    <input type="hidden" id="_name" name="me" value="{{$resource->title}}">
    <input type="hidden" id="_file" name="old_file" value="{{$resource->file_path}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <div class="form-group col-12 col-md-12">
                    <label for="name" class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{old('name', $resource->title)}}" class="form-control">
                    @error('title')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-title"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" required class="form-control selectpicker">
                        <option selected disabled value="">Nothing Selected</option>
                        @foreach($categories as $cat)
                            <option @if($cat->id == $resource->category_id) selected="selected" @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-category_id"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Company </label>
                    <select name="company_id" id="company_id" required class="form-control selectpicker">
                        <option value="">All</option>
                        @foreach($companies as $subsidiary)
                            <option value="{{ $subsidiary->id }}">{{ $subsidiary->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-company_id"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control selectpicker">
                        <option selected disabled value="">Nothing Selected</option>
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="exampleFormControlTextarea1" class="form-label">Upload Document <span class="text-danger">*</span></label>
                    <input name="file" type="file" class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                    <span class="input-note text-danger" id="error-file"> </span>
                    @error('file')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div><!--end col-->
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $('#status').selectpicker('val', '{{$resource->status}}');
    $('#category_id').selectpicker('val', '{{$resource->category_id}}');
    $('#company_id').selectpicker('val', '{{$resource->company_id}}');
</script>
