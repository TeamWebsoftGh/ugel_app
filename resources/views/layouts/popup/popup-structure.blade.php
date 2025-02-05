@if(env('SHOW_POPUP', true))
    @php
        if(!empty($popup_details)){
            $popup_class = '';
            if ($popup_details->type == 'notice'){
                $popup_class = 'notice-modal';
            }elseif($popup_details->type == 'only_image'){
                $popup_class = 'only-image-modal';
            }elseif($popup_details->type == 'promotion'){
                $popup_class = 'promotion-modal';
            }else{
                $popup_class = 'discount-modal';
            }
        }
    @endphp
    <script src="{{asset('js/countdown.jquery.js')}}"></script>
    @include('layouts.popup.popup')
@endif
