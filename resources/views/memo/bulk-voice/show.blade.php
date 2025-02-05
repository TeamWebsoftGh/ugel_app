<div class="card-body">
    <div class="mt-xl-0 mt-5">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h4>{{$announcement->title}}</h4>
                <div class="hstack gap-3 flex-wrap">
                    <div><a href="#" class="text-primary d-block"></a></div>
                    <div class="text-muted">Constituency : <span class="text-body fw-medium">{{$announcement->constituency->name}}</span>
                    </div>
                    <div class="vr"></div>
                    <div class="text-muted">Electoral Area : <span class="text-body fw-medium">{{$announcement->electoral_area->name}}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <div class="mt-4 text-muted">
            <p>{!! $announcement->description !!}</p>
        </div>
    </div>
    <!-- end row -->
</div>
