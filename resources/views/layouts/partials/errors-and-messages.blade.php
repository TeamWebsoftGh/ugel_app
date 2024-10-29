@if($errors->all())
    @foreach($errors->all() as $message)
        <div class="alert alert-warning" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            {{ $message }}
        </div>
    @endforeach
@endif
@include('layouts.partials.messages')
