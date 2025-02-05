$(document).ajaxStart(function (e) {
    $('#loading').css('display', 'block');
});
$(document).ajaxComplete(function (e) {
    $('#loading').css('display', 'none');
});

function DetermineIconFromResult(data) {
    if (data.Result === "success") {
        return "<h4><i class='fa fa-check' style='font-size:23px;color:green'></i> SUCCESS</h4><hr />";
    }
    else if (data.Result === "unauthorize") {

        return "<h4><i class='fa fa-ban' style='font-size:23px;color:blue'></i> UNAUTHORISED ACTION</h4><hr />";
    }
    else {
        return "<h4><i class='fa fa-remove' style='font-size:23px;color:red'></i> ERROR</h4><hr />";
    }
}

function HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown)
{
    if (XMLHttpRequest.status == "401") {
        var url = '/login';
        bootbox.alert("<h4><i class='fa fa-unlock' style='font-size:23px;'></i> SESSION ENDED</h4><hr /> The action cannot be completed because your logged-in session has ended. Click Ok and Login to perform this action.", function () { window.location.href = url });
    }
    else if (XMLHttpRequest.status == "500") {
        bootbox.alert("<h4><i class='fa fa-frown' style='font-size:23px;color:red'></i> SERVER ERROR</h4><hr /> The action cannot be completed because there was an application error. Please contact your administrator");
    }
    else if (XMLHttpRequest.status == "422") {
        bootbox.alert("<h4><i class='fa fa-frown' style='font-size:23px;color:red'></i> VALIDATION ERROR</h4><hr /> An error occurred");
    }
    else if (XMLHttpRequest.status == "403") {
        bootbox.alert("<h4><i class='fa fa-frown' style='font-size:23px;color:red'></i> AUTHORIZATION ERROR</h4><hr /> You are not authorized to perform this action");
    }
    else {
        bootbox.alert("<h4><i class='fa fa-chain-broken' style='font-size:23px;'></i> CONNECTION FAILURE</h4><hr /> There was a problem connecting to the server. Please check your internet connection or contact your administrator")
    }
}
$(window).on('load',function(){
    $('#change-password').modal('show');
});
function goBack() {
    window.history.back();
}
$(document).on('click', '.pdf-btn', function() {
    // $('#printable_content').html($('#summit_page').html());
    // $('#printable_content .action_div').each(function(e){$(this).remove()});
    var data = encodeURI($('#printable_content').html());
    $('#previewData').val(data);
    //console.log($('#previewData').val());
    // return;
    $('#custompdf').submit();
});
$(document).on("click", ".print-btn", function () {
    var printid = $(this).attr('data-print');
    //alert(printid);
    var restorepage = $('body').html();
    $('.printheader').show();
    var printcontent = $('#'+printid).clone();
    $('body').empty().html(printcontent);
    window.print();
    //$('body').html(restorepage);
    window.location.reload();
});
$('.bootbox-close-button').hide();
$('document').ready(function (){
    $('.dropify').dropify();
    $('.summernote').summernote({height: 150});
})

/**
 *  DATA EXPORT BUTTONS
 */
$(document).on('click', 'a.download-btn', function(e){
    let rt = $('#report-type');
    rt.val('pdf');
    rt.prop('disabled', false);
    $(this).closest('form').submit();
});
$(document).on('click', 'a.excel-export-btn', function(e){
    const rt = $('#report-type');
    alert(rt);
    rt.val('excel');
    rt.prop('disabled', false);
    $(this).closest('form').submit();
});
$(document).on('click', 'a.html-export-btn', function(e){
    const rt = $('#report-type');
    rt.val('html');
    rt.prop('disabled', false);
    $(this).closest('form').submit();
});


/**
 * Print documents
 */
$(document).on('click', '.print_btn', function(e){
    $('.report-content').html($('#report-content').html()) ;
    let $report = $('.report-content');
    $report.find('.action_div').remove() ;
    printDocument($report.html()) ;
});


/**
 * @param content
 */
function printDocument(content){
    $('#_print_form_ input').prop('disabled', false);
    $('#print-content_').val(content);
    let url = basePath + '/print' ;
    $('#_print_form_').attr('action', url).submit();
}

function initFlatpickr() {
    const fp = flatpickr(".date", {
        dateFormat: dateFormat,
        altFormat: "Y-m-d",
        autoclose: true,
        todayHighlight: true
    }); // flatpickr

    const fp2 = flatpickr(".time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    }); // flatpickr

    const fp3 = flatpickr(".datetime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: dateFormat+" H:i",
    }); // flatpickr
}
$(document).ready(function () {
    initFlatpickr(); // Initialize flatpickr on page load

    // Handle dynamic content loaded via AJAX
    $(document).ajaxComplete(function () {
        initFlatpickr(); // Reinitialize flatpickr for new elements
    });
});
