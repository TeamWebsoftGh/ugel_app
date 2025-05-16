$(document).ready(function () {
    // Show loading indicator on AJAX requests
    $(document).ajaxStart(function () {
        $('#loading').css('display', 'block');
    }).ajaxComplete(function () {
        $('#loading').css('display', 'none');
        initFlatpickr(); // Reinitialize flatpickr after AJAX loads
    });

    // Show change password modal on page load
    $('#change-password').modal('show');

    // Initialize Dropify & Summernote if available
    if ($.fn.dropify) {
        $('.dropify').dropify();
    }
    if ($.fn.summernote) {
        $('.summernote').summernote({ height: 150 });
    }

    // Handle Export Buttons
    $(document).on('click', 'a.download-btn', function () {
        $('#report-type').val('pdf').prop('disabled', false);
        $(this).closest('form').submit();
    });

    $(document).on('click', 'a.excel-export-btn', function () {
        $('#report-type').val('excel').prop('disabled', false);
        $(this).closest('form').submit();
    });

    $(document).on('click', 'a.html-export-btn', function () {
        $('#report-type').val('html').prop('disabled', false);
        $(this).closest('form').submit();
    });

    // Print Document Handling
    $(document).on('click', '.print_btn', function () {
        $('.report-content').html($('#report-content').html());
        $('.report-content .action_div').remove();
        printDocument($('.report-content').html());
    });

    // Initialize Flatpickr
    initFlatpickr();
});

// Function to Handle Errors
function HandleJSONPOSTErrors(XMLHttpRequest) {
    let errorMsg = "There was a problem connecting to the server.";
    let iconType = "error";

    switch (XMLHttpRequest.status) {
        case 401:
            Swal.fire({
                icon: "warning",
                title: "SESSION ENDED",
                text: "Your session has expired. Please log in again.",
            }).then(() => {
                window.location.href = '/login';
            });
            return;
        case 500:
            errorMsg = "Server error occurred. Please contact your administrator.";
            break;
        case 422:
            errorMsg = "Validation failed. Please check the errors and try again.";
            break;
        case 403:
            errorMsg = "You are not authorized to perform this action.";
            break;
        default:
            errorMsg = "Check your internet connection or contact support.";
    }

    Swal.fire({
        icon: iconType,
        title: XMLHttpRequest.status === 422 ? "VALIDATION ERROR" : "CONNECTION FAILURE",
        text: errorMsg,
    });
}

// Function to determine icons based on result
function DetermineIconFromResult(data) {
    let icons = {
        success: "<h4><i class='fa fa-check' style='font-size:23px;color:green'></i> SUCCESS</h4><hr />",
        unauthorize: "<h4><i class='fa fa-ban' style='font-size:23px;color:blue'></i> UNAUTHORISED ACTION</h4><hr />",
        error: "<h4><i class='fa fa-remove' style='font-size:23px;color:red'></i> ERROR</h4><hr />",
    };

    return icons[data.status] || icons.error;
}

// Function to Print a Document
function printDocument(content) {
    $('#_print_form_ input').prop('disabled', false);
    $('#print-content_').val(content);

    if (typeof basePath === "undefined") {
        console.error("Error: basePath is not defined.");
        return;
    }

    $('#_print_form_').attr('action', basePath + '/print').submit();
}

// Function to initialize Flatpickr
function initFlatpickr() {
    if (typeof flatpickr !== "undefined") {
        flatpickr(".date", {
            dateFormat: "Y-m-d",
            altFormat: "Y-m-d",
            autoclose: true,
            todayHighlight: true,
        });

        flatpickr(".time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        flatpickr(".datetime", {
            enableTime: true,
            noCalendar: false,
            dateFormat: "Y-m-d H:i",
        });
    } else {
        console.error("Error: Flatpickr is not loaded.");
    }
}

// Handle Go Back Button
function goBack() {
    window.history.back();
}

// PDF Generation
$(document).on('click', '.pdf-btn', function () {
    let data = encodeURI($('#printable_content').html());
    $('#previewData').val(data);
    $('#custompdf').submit();
});

// Print Functionality
$(document).on("click", ".print-btn", function () {
    let printid = $(this).attr('data-print');
    let restorepage = $('body').html();
    $('.printheader').show();
    let printcontent = $('#' + printid).clone();
    $('body').html(printcontent);
    window.print();
    window.location.reload();
});

function updateDropdown(url, targetId, placeholder = 'Select an option', selected = null) {
    const dropdown = $('#' + targetId);

    $.get(url)
        .done(function(response) {
            if (response.status_code === '000') {
                dropdown.empty().append(`<option value="">${placeholder}</option>`);
                response.data.forEach(item => {
                    const isSelected = selected && selected == item.id ? 'selected' : '';
                    dropdown.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
                });
            } else {
                console.warn(`Warning: ${response.message}`);
            }
        })
        .fail(function(xhr) {
            console.error("Failed to load dropdown:", xhr.responseText);
        })
        .always(function() {
            dropdown.selectpicker?.('refresh');
        });
}

// Handle attachment deletion
$(document).on('click', '.delete-attachment', function () {
    const $btn = $(this);
    const url = $btn.data('url');
    const container = $btn.closest('.s_attach');

    if (!confirm("Are you sure you want to delete this attachment?")) return;

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            container.fadeOut(300, function () {
                $(this).remove();
            });
        },
        error: function () {
            alert('Failed to delete the attachment.');
        }
    });
});
