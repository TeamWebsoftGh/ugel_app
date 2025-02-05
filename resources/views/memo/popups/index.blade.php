@extends('layouts.main')

@section('title', "Popup Builder")
@section('page-title', "Popup Builder")

@section('button')
    <a class='btn btn-info text-white px-4 align-self-center report-btn' href="{{route('popups.create')}}">Add Popup</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Popups
                        @if(user()->can('create-announcements'))
                            <span style="float: right"><a href="{{route('popups.create')}}" class="btn btn-primary">Add Popup</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Display Period</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1 @endphp
                            @forelse($popups as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{ucwords(str_replace('_',' ',$data->type))}}</td>
                                    <td>{{$data->duration}}</td>
                                    <td>{{$data->is_active?'Active':'Inactive'}}</td>
                                    <td>
                                        <a href="{{route("popups.show", $data->id)}}" class="btn btn-sm btn-info text-white show_modal_demo"
                                           data-type="{{$data->type}}"
                                           data-title="{{$data->title}}"
                                           data-description="{{$data->description}}"
                                           data-only_image="{{$data->only_image}}"
                                           @php
                                               $image_url = !empty($data->only_image) ? asset($data->only_image) : '';
                                           @endphp
                                           data-imageurl="{{$image_url}}"
                                           @php
                                               $bg_image_url = !empty($data->cover_image) ? asset($data->cover_image) : '';
                                           @endphp
                                           data-background_image="{{$bg_image_url}}"
                                           data-button_text="{{$data->button_text}}"
                                           data-button_link="{{$data->button_link}}"
                                           data-btn_status="{{$data->btn_status}}"
                                           data-offer_time_end="{{$data->offer_time_end}}"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{route("popups.edit", $data->id)}}" class="btn btn-sm btn-success text-white"><i class="mdi mdi-file-edit"></i></a>
                                        <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-campaign-{{ $data->id }}"><i class="mdi mdi-trash-can text-white"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade " id="delete-campaign-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Campaign</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure  you  want to delete <b class='text-danger'>{{$data->name}}</b> ?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('popups.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nx-popup-backdrop"></div>
    <div class="nx-popup-wrapper ">
        <div class="nx-modal-content-wrapper">
            <div class="nx-modal-inner-content-wrapper">
                <div class="nx-popup-close">&times;</div>
                <div class="nx-modal-content">
                </div>
            </div>
        </div>
    </div>

@endsection
@section("js")
    @include("layouts.shared.datatable")
    <script src="{{asset('js/countdown.jquery.js')}}"></script>
    <script>
        $(document).on('click','.show_modal_demo',function (e) {
            e.preventDefault();
            var el = $(this);
            var type = el.data('type');
            console.log(type);
            setTimeout(function () {
                $('.nx-popup-backdrop').addClass('show');
                $('.nx-popup-wrapper').addClass('show');
            });
            showPopupDemo(type,el);

        });

        function showPopupDemo(type,el){
            if(type == 'notice'){
                $('.nx-popup-wrapper').addClass('notice-modal');
                $('.nx-modal-content').html(' <div class="notice-modal-content-wrapper">\n' +
                    '<div class="right-side-content">\n' +
                    '<h4 class="title">'+el.data('title')+'</h4>\n' +
                    '<p>'+el.data('description')+'</p>\n' +
                    '</div>\n' +
                    '</div>');
            }else if(type == 'only_image'){
                $('.nx-popup-wrapper').addClass('only-image-modal');
                $('.nx-popup-wrapper.only-image-modal .nx-modal-inner-content-wrapper').css({
                    'background-image' : 'url('+el.data('imageurl')+')'
                });
            }else if(type == 'promotion'){

                $('.nx-popup-wrapper').addClass('promotion-modal');
                $('.nx-popup-wrapper.promotion-modal .nx-modal-inner-content-wrapper').css({
                    'background-image' : 'url('+el.data('background_image')+')'
                })
                $('.nx-modal-content').html('<div class="promotional-modal-content-wrapper">\n' +
                    '<div class="left-content-warp">\n' +
                    '<img src="'+el.data('imageurl')+'" alt="">\n' +
                    '</div>\n' +
                    '<div class="right-content-warp">\n' +
                    '<div class="right-content-inner-wrap">\n' +
                    '<h4 class="title">'+el.data('title')+'</h4>\n' +
                    '<p>'+el.data('description')+'</p>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '</div>');

                if(el.data('btn_status') == 'on'){
                    $('.promotional-modal-content-wrapper .right-content-warp .right-content-inner-wrap').append('<div class="btn-wrapper"><a href="'+el.data('button_link')+'" class="btn-boxed">'+el.data('button_text')+'</a></div>');
                }

            }else{
                $('.nx-popup-wrapper').addClass('discount-modal');
                $('.nx-popup-wrapper.discount-modal .nx-modal-inner-content-wrapper').css({
                    'background-image' : 'url('+el.data('background_image')+')'
                })
                $('.nx-modal-content').html('<div class="discount-modal-content-wrapper">\n' +
                    '<div class="left-content-warp">\n' +
                    '<img src="'+el.data('imageurl')+'" width="250px" alt="">\n' +
                    '</div>\n' +
                    '<div class="right-content-warp">\n' +
                    '<div class="right-content-inner-wrap">\n' +
                    '<h4 class="title">'+el.data('title')+'</h4>\n' +
                    '<p>'+el.data('description')+'</p>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '</div>');
                if(el.data('offer_time_end')){
                    $('.discount-modal-content-wrapper .right-content-warp .right-content-inner-wrap').append('<div class="countdown-wrapper"><div id="countdown"></div></div>');
                }
                if(el.data('btn_status') == 'on'){
                    $('.discount-modal-content-wrapper .right-content-warp .right-content-inner-wrap').append('<div class="btn-wrapper"><a href="'+el.data('button_link')+'" class="btn-boxed">'+el.data('button_text')+'</a></div>');
                }

                console.log(el.data('offer_time_end'));

                var offerTime = el.data('offer_time_end');
                var year = offerTime.substr(0,4);
                var month = offerTime.substr(5,2);
                var day = offerTime.substr(8,2);

                $('#countdown').countdown({
                    year: year,
                    month: month,
                    day: day,
                    labels: true,
                    labelText: {
                        'days': "{{__('days')}}",
                        'hours': "{{__('hours')}}",
                        'minutes': "{{__('min')}}",
                        'seconds': "{{__('sec')}}",
                    }
                });

            }
        }

        $(document).on('click','.nx-popup-close,.nx-popup-backdrop',function (e) {
            e.preventDefault();
            $('.nx-modal-inner-content-wrapper').removeAttr('style');
            $('.nx-modal-content').html('');
            $('.nx-popup-wrapper').removeClass('only-image-modal');
            $('.nx-popup-wrapper').removeClass('notice-modal');
            $('.nx-popup-backdrop').removeClass('show');
            $('.nx-popup-wrapper').removeClass('show');

        });
        $('.edit_btn').on('click', function () {
            event.preventDefault();
            var par = $(this).parent().parent()
            par.find('.edit_btn').hide();
            par.find('.save_btn').show();
            par.find('input').show();
            par.find('span').hide();
            par.find('.cancel_btn').show();
            par.find('.delete_btn').hide();

        });
        $('.cancel_btn').on('click', function () {
           event.preventDefault();
           var par = $(this).parent().parent()
           par.find('.edit_btn').show();
           par.find('.save_btn').hide();
           par.find('input').hide();
           par.find('span').show();
           par.find('.cancel_btn').hide();
           par.find('.delete_btn').show();

        });
        $('.add_btn').on('click', function () {
            event.preventDefault();
            $('#myTable').append('<tr><td>my data</td><td>more data</td></tr>');

        });
        $('.save_btn').on('click', function () {
            event.preventDefault();
            var url = $(this).attr('href');
            alert(url);
            var par = $(this).parent().parent()
            $('#inputfile').change(function(){
                var file_data = $('#inputfile').prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    url: url,
                    type: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data){
                        console.log(data);
                    }
                });
            });
        })
    </script>
@endsection
