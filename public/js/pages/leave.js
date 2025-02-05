
$(function () {
   'use strict';
    let token = $('meta[name="csrf-token"]').attr('content');

    $('.start_date').change(function (e)
    {
        const start_date = $(this).val(),
            duration = $('#total_days').val(),
            leave_type_id = $('#leave_type_id').val();
        if($.trim(leave_type_id) === '' ){alert('Please select the Leave Type');}
        if ($.trim(duration) === '') {
            alert('Please specify a duration!');
        } else {
            $.post('/timesheet/leaves/check-for-holiday-or-weekend',{_token:token, start_date:start_date},
                 function (e) {
                    if (e == 0) { //date is not a weekend nor holiday
                        $.post('/timesheet/leaves/get-leave-end-date',{_token:token, start_date:start_date, duration:duration, leave_type_id:leave_type_id},
                            function (e) {
                                const end_date = e;
                                $('.end_date').val(end_date);
                                $.post('/timesheet/leaves/get-leave-resume-date',{_token:token, end_date:end_date, leave_type_id:leave_type_id},function(e){
                                    $('.resumption_date').val(e);
                                });
                            });
                    } else {
                        $('.end_date').val('');
                        $('.resumption_date').val('');
                        alert('Selected date is a holiday or a weekend.')
                    }
                }
            );
        }

    });
    $('#total_days').on('input', function () {
        $('.start_date').val('');
        $('.end_date').val('');
        $('.resumption_date').val('');
    });


    // $.get('/get-staff-names-of-department', function(data){
    //     $('#reliever-names').autocomplete({
    //         lookup: data,
    //         onSelect: function (suggestion) {
    //             $('#_staff_id__').val(suggestion.data);
    //             $('#reliever-staff-id').val(suggestion.data);
    //         }
    //     });
    // });
    //
    // // Request leave for someone else by supervisor
    // $.get('/get-staff-names', function(data){
    //     $('#employee-name').autocomplete({
    //         lookup: data,
    //         onSelect: function (suggestion) {
    //             const staff_id = suggestion.data ;
    //             $('#employee-staff-id').val(staff_id) ;
    //             $('#_staff_id___').val(staff_id);
    //             $('#employee-name').val(suggestion.value) ;
    //         }
    //     });
    // });
    //
    // $(document).on('input', '#employee-name', function(e){
    //     const v = $.trim($(this).val());
    //     if(v === '' || v.length === 0){
    //         $('#employee-staff-id').val('') ;
    //         $('#_staff_id___').val('') ;
    //     }
    // });
    //
    // $(document).on('click', '.view-details', function(){
    //     const recid = $(this).closest('tr').attr('data-id');
    //     $(this).attr('href', appUrl + 'record-details?recid='+recid);
    // }) ;

});
