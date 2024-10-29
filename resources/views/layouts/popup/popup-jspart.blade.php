@php
    if(empty($popup_details)) {return;}
@endphp


<script>
    $(document).ready(function () {

        var delayTime = "{{env('POPUP_DELAY_TIME', 1000)}}";
        var popupBackdrop =  $('.nx-popup-backdrop');
        var popupWrapper =  $('.nx-popup-wrapper');

        delayTime = delayTime ? delayTime : 1000;

       if (getCookie('nx_popup_show') == '') {
            setTimeout(function () {
                popupBackdrop.addClass('show');
                popupWrapper.addClass('show');

            }, parseInt(delayTime));
            setCookie('nx_popup_show', 'no', 1);
       }

        $(document).on('click', '.nx-popup-close,.nx-popup-backdrop', function (e) {
            e.preventDefault();
            $('.nx-modal-content').html('');
            popupBackdrop.removeClass('show');
            popupWrapper.removeClass('show');
            setCookie('nx_popup_show', 'no', 1);
        });

        var offerTime = "{{$popup_details->offer_time_end}}";
        var year = offerTime.substr(0, 4);
        var month = offerTime.substr(5, 2);
        var day = offerTime.substr(8, 2);
        if (offerTime && $('#countdown').length > 0) {
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
    });
</script>
    <script>
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
    </script>
