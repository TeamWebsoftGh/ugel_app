<div class="">
    <div class="card-body">
        @if(!$ticket->is_closed)
            <form method="POST" id="fileUploadForm" action="{{route("support-tickets.file-upload")}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                <div class="row g-3">
                    <div class="col-lg-6 col-md-12">
                        <label for="exampleFormControlTextarea1" class="form-label">Upload Document</label>
                        <input name="ticket_files[]" type="file" multiple class="form-control bg-light border-light" id="exampleFormControlTextarea1"/>
                        <span class="input-note text-danger" id="error-ticket_files"> </span>
                        @error('ticket_files')
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
                            <div class="d-flex align-items-center text-wrap">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                        <img src="{{$file->Icon}}">
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="fs-15 mb-0"><a target="_blank" href="{{asset($file->file_path)}}">{{$file->original_file_name??"N/A"}}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td>{{$file->created_at}}</td>
                        <td>{{$file->owner->fullname}}</td>
                        <td class="table-action">
                            <div class="btn-group">
                                <a class="btn btn-sm btn-info" target="_blank" href="{{asset($file->file_path)}}">View</a></li>
                                @if($file->user_id == user()->id && !$ticket->is_closed)
                                    <a class="btn btn-sm btn-danger" onclick="DeleteItem('{{$file->original_file_name}}', '{{route("support-tickets.delete-file", ['ticket_id' => $ticket->id, 'id'=> $file->id])}}')" href="javascript:void(0);">Delete</a></li>
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
