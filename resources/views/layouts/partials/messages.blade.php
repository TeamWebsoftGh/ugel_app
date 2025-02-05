@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <p><span>Success!</span> {!! session('success') !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>

@elseif(session('error'))

    <div class="alert alert-error alert-dismissible fade show" role="alert">
        <p><span>Error!</span> {!! session('error')  !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@elseif(session('message'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <p><span>Success!</span> {!! session('message')  !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>

@elseif(session('warning'))

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <p><span>Error!</span> {!! session('warning')  !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
