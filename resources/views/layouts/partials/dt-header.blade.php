<div class="card-header d-flex align-items-center">
    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
    <div>
        @if(user()->can(['create-'.get_permission_name()]) && !isset($hide))
            <button type="button" class="btn btn-primary ms-auto add_dt_btn" data-url="{{url()->current()}}/create">Add New</button>
            @isset($import)
                <a href="{{url()->current()}}/import" class="btn btn-soft-info ms-auto">Import</a>
            @endisset
        @endif
    </div>
</div>
