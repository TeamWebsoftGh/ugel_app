<form method="POST" action="{{route('resource.knowledge-base.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$topic->id}}">
    <input type="hidden" id="_name" name="me" value="{{$topic->topic}}">
    <div class="row clearfix">
        <div class="col-sm-12 col-lg-12">
            <div class="row">
                <div class="form-group col-12 col-md-12">
                    <label for="name" class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{old('name', $topic->title)}}" class="form-control">
                    @error('title')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-title"> </span>
                </div>
                <div class="form-group col-12 col-md-4">
                    <label for="name" class="control-label">Select Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="categories" required class="form-control selectpicker">
                        @foreach($categories as $cat)
                            <option @if($cat->id == $topic->category_id) selected="selected" @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-category_id"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control selectpicker">
                        <option value="0" @if($topic->status == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($topic->status == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="exampleFormControlTextarea1" class="form-label">Upload Document(s)</label>
                    <input name="kb_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                    <span class="input-note text-danger" id="error-kb_files"> </span>
                    @error('kb_files')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div><!--end col-->
                <div class="form-group col-12">
                    <label for="benefits" class="control-label">Content</label>
                    <textarea class="form-control summernote" name="content" id="content">{!! $topic->content  !!}</textarea>
                    @error('content')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    <span class="input-note text-danger" id="error-content"> </span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
            @if(isset($files))
                <div data-simplebar style="max-height: 380px;">
                    @forelse($files as $file)
                        <div class="d-flex align-items-center mt-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                    <img src="{{$file->Icon}}">
                                </div>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fs-15 mb-0"><a target="_blank" href="{{asset("storage/".$file->file_path)}}">{{$file->original_file_name??"N/A"}}</a></h6>
                                <a onclick="DeleteItem('{{$file->original_file_name}}', '{{route("knowledge-base.delete-file", ['topic_id' => $topic->id, 'id'=> $file->id])}}')" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</form>
