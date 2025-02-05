<div data-simplebar style="max-height: 480px;" class="table-responsive">
    <!-- Left Icon Accordions -->
    <div class="accordion lefticon-accordion custom-accordionwithicon accordion-border-box" id="accordionlefticon">
        @forelse($resources as $files)
            <div class="accordion-item">
                <h2 class="accordion-header" id="accordionlefticonExample1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{$files->first()->slug}}" aria-expanded="false" aria-controls="accor_lefticonExamplecollapse1">
                        {{$files->first()->category->name}}
                    </button>
                </h2>
                <div id="{{$files->first()->slug}}" class="accordion-collapse collapse" aria-labelledby="accordionlefticonExample1" data-bs-parent="#accordionlefticon">
                    <div class="accordion-body">
                        @forelse($files as $file)
                            <div class="d-flex align-items-center mt-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                        <img src="{{$file->Icon}}">
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="fs-15 mb-0"><a target="_blank" href="{{asset($file->file_path)}}">{{$file->title??"N/A"}}</a></h6>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            <h6></h6>
        @empty
        @endforelse
    </div>
</div>
