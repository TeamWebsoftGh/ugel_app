@extends('layouts.main')

@section('title', 'Knowledge Base')
@section('page-title', $topic->title)

@section("content")
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-xl-0 mt-5">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4>{{$topic->title}}</h4>
                                <div class="hstack gap-3 flex-wrap">
                                    <div><a href="#" class="text-primary d-block"></a></div>
                                    <div class="text-muted">Category : <span class="text-body fw-medium">{{$topic->categoryName}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Published : <span class="text-body fw-medium">{{$topic->created_at}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>

                        <div class="mt-4 text-muted">
                            <p>{!! $topic->content !!}</p>
                        </div>
                        <!-- end card body -->
                        <h4 class="card-title">Resources</h4>
                        <div data-simplebar style="max-height: 380px;" class="table-responsive">
                            @forelse($files as $file)
                                <div class="d-flex align-items-center mt-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                            <img src="{{$file->Icon}}">
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="fs-15 mb-0"><a target="_blank" href="{{asset($file->file_path)}}">{{$file->original_file_name??"N/A"}}</a></h6>
                                    </div>
                                </div>
                            @empty
                                No resources
                            @endforelse
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
