<div class="">
    <div class="card-body">
        @if(!$task->is_closed)
        <form method="POST" id="fileUploadForm" action="{{route("tasks.file-upload")}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="task_id" value="{{$task->id}}">
            <div class="row g-3">
                <div class="col-lg-6 col-md-12">
                    <label for="exampleFormControlTextarea1" class="form-label">Upload Document</label>
                    <input name="task_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                    <span class="input-note text-danger" id="error-task_files"> </span>
                    @error('task_files')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div><!--end col-->
                <div class="col-12">
                    <button type="submit" class="btn btn-success"><i class="las la-cloud-upload-alt"></i> Upload</button>
                </div>
            </div><!--end row-->
        </form>
        <hr/>
        @endif
        <div data-simplebar style="max-height: 380px;" class="table-responsive">
            <table class="table table-borderless align-middle mb-0 basic-datatable">
                <thead class="table-light text-muted">
                <tr>
                    <th scope="col">File Name</th>
                    <th scope="col">Upload Date</th>
                    <th scope="col">Upload By</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($files as $file)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                        <img src="{{$file->Icon}}">
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="fs-15 mb-0"><a target="_blank" href="{{asset("storage/".$file->file_path)}}">{{$file->original_file_name??"N/A"}}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>{{$file->created_at}}</td>
                        <td>{{$file->createdBy->fullname}}</td>
                        <td class="table-action">
                            <div class="btn-group">
                                <a class="btn btn-sm btn-info" target="_blank" href="{{asset("storage/".$file->file_path)}}">View</a></li>
                                @if($file->user_id == user()->id && !$task->is_closed)
                                    <a class="btn btn-sm btn-danger" onclick="DeleteItem('{{$file->original_file_name}}', '{{route("tasks.delete-file", ['task_id' => $task->id, 'id'=> $file->id])}}')" href="javascript:void(0);">Delete</a></li>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table><!--end table-->
        </div>
    </div>
</div>
