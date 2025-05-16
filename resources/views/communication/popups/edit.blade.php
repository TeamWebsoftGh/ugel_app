@extends('layouts.cms')

@section('title', 'Edit Campaign')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('cms.campaigns.index')}}">Campaigns</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.errors-and-messages')
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Edit Campaign</h4>
                    <form method="post" id="needs-validation" novalidate action="{{route("cms.campaigns.update", $campaign->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">
                            <label for="title">Subject</label>
                            <input type="text" class="form-control{{ $errors->has('subject') ? ' parsley-error' : '' }}" name="subject" required value="{{old('subject', $campaign->subject)}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Content</label>
                            <textarea class="summernote" name="content">{{old('content', $campaign->content)}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Upload Attachment</label>
                            <input type="file" data-default-file="{{asset("uploads/".$campaign->attachment)}}"  name="attachment" data-max-file-size="5M" class="dropify" >
                        </div>
                        <div class="form-group ml-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="sendmail" id="customCheck2" data-parsley-multiple="groups">
                                <label class="custom-control-label" for="customCheck2">Resend to all Subscribers</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{route("cms.campaigns.index")}}" class="btn btn-danger"> Go Back</a>
                                <button type="submit" class="btn btn-primary"> Update campaign</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end card-body-->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("dist/plugins/summernote/summernote-bs4.min.js")}}"></script>
    <script>
        $('.summernote').summernote({
            height: 240,   //set editable area's height
            codemirror: { // codemirror options
                theme: 'monokai'
            }
        });
    </script>
@endsection
