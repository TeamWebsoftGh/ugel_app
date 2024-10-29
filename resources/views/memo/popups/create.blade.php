@extends('layouts.main')

@section('title', 'Build a Popup')
@section('page-title', 'Build a Popup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('popups.index')}}">Popups</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Popups</h4>
                    @if($errors->all())
                        @foreach($errors->all() as $message)
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">×</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                {{ $message }}
                            </div>
                        @endforeach
                    @endif
                    <form method="post" id="needs-validation" novalidate action="{{route("popups.store")}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$popup->id}}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name">{{__('Name ( It will not show in frontend )')}}</label>
                                        <input type="text" class="form-control"  id="name" name="name" value="{{old('name', $popup->name)}}" placeholder="{{__('Name')}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="type"><strong>{{__('Type')}}</strong></label>
                                        <select name="type" id="popup_type" class="form-control">
                                            <option @if(old('type', $popup->type) == 'notice') selected @endif value="notice">{{__('Notice')}}</option>
                                            <option @if(old('type', $popup->type) == 'only_image') selected @endif value="only_image">{{__('Only Image')}}</option>
                                            <option @if(old('type', $popup->type) == 'promotion') selected @endif value="promotion">{{__('Promotion')}}</option>
                                            <option @if(old('type', $popup->type) == 'discount') selected @endif value="discount">{{__('Discount')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="start_date">{{__('Start Date')}}</label>
                                        <input type="date" class="form-control datepicker" id="start_date" name="start_date" value="{{old('start_date', $popup->start_date)}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end_date">{{__('End Date')}}</label>
                                        <input type="date" class="form-control datepicker"  id="end_date" name="end_date" value="{{old('end_date', $popup->end_date)}}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="title">{{__('Title')}}</label>
                                        <input type="text" class="form-control"  id="title" name="title" value="{{old('title', $popup->title)}}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="description">{{__('Description')}}</label>
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="6">{{old('title', $popup->description)}}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="offer_time_end">{{__('Offer End Date')}}</label>
                                        <input type="date" class="form-control datepicker" id="offer_time_end" name="offer_time_end" value="{{old('title', $popup->offer_time_end)}}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="btn_status"><strong>{{__('Button Show/Hide')}}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="btn_status" id="btn_status" @if(old('title', $popup->btn_status)) checked @endif>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="button_text">{{__('Button Text')}}</label>
                                        <input type="text" class="form-control"  id="button_text" name="button_text" value="{{old('button_text', $popup->button_text)}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="button_link">{{__('Button Link')}}</label>
                                        <input type="text" class="form-control"  id="button_link" name="button_link" value="{{old('button_link', $popup->button_link)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="background_image">{{__('Background Image')}}</label>
                                    <input type="file" class="dropify" @if($popup->cover_image) data-default-file="{{asset($popup->cover_image)}}" @endif name="background_image" data-max-file-size="5M">
                                    <small>{{__('Recommended image size 700x400')}}</small>
                                </div>

                                <div class="form-group">
                                    <label for="image">{{__('Image')}}</label>
                                    <input type="file" class="dropify" @if($popup->only_image) data-default-file="{{asset($popup->only_image)}}" @endif name="image" data-max-file-size="5M">
                                    <small>{{__('Recommended image size 700x400')}}</small>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Save Popup')}}</button>
                    </form>
                </div>
                <!--end card-body-->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            showHideFields($('#popup_type').val());
            $(document).on('change','#popup_type',function (e) {
                e.preventDefault();
                var el = $(this);
                var type = el.val();
                showHideFields(type);
            });

            function showHideFields(type) {
                console.log(type);
                if(type == 'notice'){
                    $('label[for="image"]').parent().hide();
                    $('label[for="description"]').parent().show();
                    $('label[for="title"]').parent().show();
                    $('label[for="background_image"]').parent().hide();
                    $('label[for="button_text"]').parent().hide();
                    $('label[for="button_link"]').parent().hide();
                    $('label[for="btn_status"]').parent().hide();
                    $('label[for="offer_time_end"]').parent().hide();

                }else if(type == 'only_image'){
                    $('label[for="image"]').parent().show();
                    $('label[for="background_image"]').parent().hide();
                    $('label[for="button_text"]').parent().hide();
                    $('label[for="button_link"]').parent().hide();
                    $('label[for="btn_status"]').parent().hide();
                    $('label[for="offer_time_end"]').parent().hide();
                    $('label[for="description"]').parent().hide();
                    $('label[for="title"]').parent().hide();
                }else if(type == 'promotion'){
                    $('label[for="image"]').parent().show();
                    $('label[for="background_image"]').parent().show();
                    $('label[for="button_text"]').parent().show();
                    $('label[for="button_link"]').parent().show();
                    $('label[for="btn_status"]').parent().show();
                    $('label[for="offer_time_end"]').parent().hide();
                    $('label[for="description"]').parent().show();
                    $('label[for="title"]').parent().show();
                }else{
                    $('label[for="image"]').parent().show();
                    $('label[for="background_image"]').parent().show();
                    $('label[for="button_text"]').parent().show();
                    $('label[for="button_link"]').parent().show();
                    $('label[for="btn_status"]').parent().show();
                    $('label[for="offer_time_end"]').parent().show();
                    $('label[for="description"]').parent().show();
                    $('label[for="title"]').parent().show();
                }
            }
        });
    </script>
@endsection
