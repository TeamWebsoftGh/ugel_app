<h5 class="card-title mb-4">Audit Trail</h5>
<div data-simplebar style="max-height: 480px;" class="px-3 mx-n3 mb-2">
    @foreach ($logs as $log)
        <div class="acitivity-item py-3 d-flex">
            <div class="flex-shrink-0">
                <img src="{{asset($log->user->Userimage)}}" alt="" class="avatar-xs rounded-circle acitivity-avatar">
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">{{ isset($log->user)? $log->user->fullname:'System' }}</h6>
                <p class="text-muted mb-2" title="{!! optional($log->logAction)->name !!}"><i class="ri-file-text-line align-middle ms-2"></i> {!! $log->description??$log->message !!}</p>
                <small class="mb-0 text-muted"><i class="ri-timer-2-fill align-middle ms-2"></i> {{ $log->updated_at->diffForHumans() }} @if(user()->hasRole('developer')) | {{$log->client_ip}}@endif</small>
            </div>
        </div>
    @endforeach
</div>
