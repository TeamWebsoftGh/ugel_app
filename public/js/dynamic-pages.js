

$(function(){
    'use strict';

    $(document).on('change', '#parent-menu-id', function (e) {
        $('#sub-menu-id >option').remove();
        $('#sub-menu-id').append('<option value="">Select Option</option>');
        var id = $(this).val();
        $.get('/get-sub-menus/'+id,function(data){
            for (let i = 0; i < data.length; i++) {
                $("#sub-menu-id").append(new Option(data[i].name, data[i].id));
            }
        });
    })

});
