<h5 class="card-title mb-4">Comments</h5>
<div data-simplebar style="max-height: 320px;" class="px-3 mx-n3 mb-2">
    <div id="messages">
        @forelse($task->taskComments()->orderBy('updated_at', 'desc')->get() as $comment)
            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <img src="{{asset("storage/".$comment->sender->UserImage)}}" alt="" class="avatar-xs rounded-circle" />
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fs-13">
                        <a href="javascript:void(0)">{{$comment->sender->fullname}}</a>
                        <small class="text-muted">{{$comment->created_at}}</small>
                        @if($comment->user_id == user()->id)
                            <a href="javascript:void(0)" onclick="DeleteItem('{{$comment->message}}', '{{route("tasks.comments.destroy", ['task_id'=>$task->id, 'id' => $comment->id])}}')" class="text-danger"><i class="las la-trash"></i></a>
                        @endif
                    </h5>
                    <p class="text-muted">{{nl2br($comment->message)}}</p>
                </div>
            </div>
        @empty
            No Comments
        @endforelse
    </div>
</div>
@if(!$task->is_closed)
    <form class="mt-4" method="POST" id="messageForm" action="{{route("tasks.comments.store")}}">
        @csrf
        <input type="hidden" name="task_id" value="{{$task->id}}">
        <div class="row g-3">
            <div class="col-lg-12">
                <label for="exampleFormControlTextarea1" class="form-label">Add Comment</label>
                <textarea name="message" class="form-control bg-light border-light" id="exampleFormControlTextarea1" rows="3" placeholder="Enter comments"></textarea>
                <span class="input-note text-danger" id="error-message"> </span>
                @error('message')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div><!--end col-->
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success">Post Comments</button>
            </div>
        </div><!--end row-->
    </form>
@endif
