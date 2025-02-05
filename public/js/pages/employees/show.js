let mtoken = $('meta[name="csrf-token"]').attr('content');

$('select[name="gender"]').val($('input[name="gender_hidden"]').val());
$('#marital_status').selectpicker('val', $('input[name="marital_status_hidden"]').val());
$('#is_ssf_contributor').selectpicker('val', $('input[name="is_ssf_contributor_hidden"]').val());
$('#is_tax_payer').selectpicker('val', $('input[name="is_tax_payer_hidden"]').val());
$('#supervisor_id').selectpicker('val', $('input[name="supervisor_id_hidden"]').val());
$('#title').selectpicker('val', $('input[name="title_hidden"]').val());

$('#company_id').selectpicker('val', $('input[name="company_id_hidden"]').val());
$('#department_id').selectpicker('val', $('input[name="department_id_hidden"]').val());
$('#designation_id').selectpicker('val', $('input[name="designation_id_hidden"]').val());
$('#location_id').selectpicker('val', $('input[name="location_id_hidden"]').val());

$('#employee_type_id').selectpicker('val', $('input[name="employee_type_id_hidden"]').val());
$('#employee_category_id').selectpicker('val', $('input[name="employee_category_id_hidden"]').val());
$('#office_shift_id').selectpicker('val', $('input[name="office_shift_id_hidden"]').val());
$('#staff_type').selectpicker('val', $('input[name="staff_type_hidden"]').val());
$(document).ready(function ()
{
    $('.dt-link').click(function() {
        var tabId = $(this).attr('data-table');
        var url = $(this).attr('data-url');
        // Destroy existing DataTable instance, if any
        // Save data to session storage
        sessionStorage.setItem('tabId',tabId);
        $('#' + tabId + '-table').DataTable().destroy();

        // Load data from the specified URL and initialize the DataTable
        loadDataAndInitializeDataTable(tabId, url);
    });

    $('#core_hrm').click(function() {
        $('#awards-tab').trigger('click');
    });
});

function loadDataAndInitializeDataTable(tabId, url) {
    var table = $('#' + tabId + '-table');

    table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url
        },
        columns: getColumnsForTab(tabId),
        order: [],
        initComplete: function(settings, json) {
            // Handle row clicks for this DataTable
            $(this).on('click', 'tr .dt-edit', function() {
                var dataId = $(this).data('id');
                //console.log(data);
                console.log(dataId);
                openEditModal(tabId, url, dataId);
            });
            $(this).on('click', 'tr .dt-show', function() {
                var dataId = $(this).data('id');
                //console.log(data);
                console.log(dataId);
                openShowModal(tabId, url, dataId);
            });
            $(this).on('click', 'tr .dt-delete', function() {
                var dataId = $(this).data('id');
                console.log('Delete button clicked for row with ID:', dataId);
                dtDeleteItem(dataId, url, tabId);
            });
        },
    });
}

function getColumnsForTab(tabId) {
    // Define the column configuration for each table
    var columnsMap = {
        'qualifications': [
            {data: 'institution_name', name: 'institution_name'},
            {data: null, render: function(data, type, row) { return row.start_date + ' to ' + row.end_date; }},
            {data: 'certificate', name: 'certificate'},
            {data: 'education_level', name: 'education_level'},
            {data: 'action', name: 'action', orderable: false}
        ],
        'emergency': [
            { data: 'contact_name', name: 'contact_name' },
            { data: 'contact_type', name: 'contact_type' },
            { data: 'contact_relation', name: 'contact_relation' },
            { data: 'email', name: 'email' },
            { data: 'personal_phone', name: 'personal_phone' },
            { data: 'action', name: 'action', orderable: false }
        ],
        'employment': [
            { data: 'company_name', name: 'company_name' },
            { data: 'job_title', name: 'job_title' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', orderable: false }
        ],
        'immigration': [
            { data: 'document', name: 'document' },
            { data: 'issue_date', name: 'issue_date' },
            { data: 'expiry_date', name: 'expiry_date' },
            { data: 'country', name: 'country' },
            { data: 'eligible_review_date', name: 'eligible_review_date' },
            { data: 'action', name: 'action', orderable: false }
        ],
       'awards':[
           { data: 'award_name', name: 'award_name' },
           {
               data: null,
               render: function (data) {
                   return "<b>Info: </b> " + data.award_information + "<br><b>Cash: </b> " + data.cash;
               }
           },
           { data: 'gift', name: 'gift' },
           { data: 'award_date', name: 'award_date' },
           { data: 'action', name: 'action', orderable: false }
       ],
        'designation_change':[
            {data: 'employee_name', name: 'employee_name'},
            {data: 'old_designation_name', name: 'old_designation_name'},
            {data: 'new_designation_name', name: 'new_designation_name'},
            {data: 'change_date', name: 'change_date'},
            { data: 'action', name: 'action', orderable: false }
        ],
        'transfers':[
            {data: 'employee_name', name: 'employee_name'},
            {data: 'transfer_type', name: 'transfer_type'},
            {data: 'from_department', name: 'from_department'},
            {data: 'to_department', name: 'to_department'},
            {data: 'from_branch_name', name: 'from_branch_name'},
            {data: 'to_branch_name', name: 'to_branch_name'},
            {data: 'action', name: 'action', orderable: false }
        ],
        'travels':[
            {data: 'employee_name', name: 'employee_name'},
            {data: 'travel_type_name', name: 'travel_type_name'},
            {data: 'purpose_of_visit', name: 'purpose_of_visit'},
            {data: 'place_of_visit', name: 'place_of_visit'},
            {data: 'duration', name: 'duration'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false }
        ],
        'complaints':[
            {data: 'complaint_from', name: 'complaint_from'},
            {data: 'complaint_from_department', name: 'complaint_from_department'},
            {data: 'complaint_against', name: 'complaint_against'},
            {data: 'complaint_against_department', name: 'complaint_against_department'},
            {data: 'complaint_title', name: 'complaint_title'},
            {data: 'complaint_date', name: 'complaint_date'},
            { data: 'action', name: 'action', orderable: false }
        ],
        'offenses':[
            {data: 'employee_name', name: 'employee_name'},
            {data: 'department_name', name: 'department_name'},
            {data: 'offense_type_name', name: 'offense_type_name'},
            {data: 'offence_date', name: 'offence_date'},
            {data: 'warning_type_name', name: 'warning_type_name'},
            {data: 'warning_date', name: 'warning_date'},
            { data: 'action', name: 'action', orderable: false }
        ],
        'assets':[
            {data: 'document', name: 'document'},
            {data: 'category', name: 'category'},
            {data: 'asset_code', name: 'asset_code'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'status', name: 'status'},
            {data: 'employee', name: 'employee'},
            {data: 'department', name: 'department'},
            {data: 'action', name: 'action', orderable: false }
        ]
    };

    return columnsMap[tabId] || [];
}
function openEditModal(rowData, url, id)
{
    // Make an AJAX request to fetch the modal content
    var newUrl = removeQueryParamsFromUrl(url);
    $.ajax({
        url: newUrl+'/'+id+'/edit',
        type: 'GET',
        success: function(response) {
            // Update the modal content with the fetched content
            $('#editModal .modal-form-body').html(response);
            $('.selectpicker').selectpicker('refresh');
            // Show the edit modal
            $('#editModal .modal-title').text("Edit Record");
            $('#editModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching modal content: ' + error);
        }
    });
}

function openShowModal(rowData, url, id)
{
    // Make an AJAX request to fetch the modal content
    var newUrl = removeQueryParamsFromUrl(url);
    $.ajax({
        url: newUrl+'/'+id+'/edit',
        type: 'GET',
        success: function(response) {
            // Update the modal content with the fetched content
            $('#editModal .modal-form-body').html(response);
            $('.selectpicker').selectpicker('refresh');
            // Show the edit modal
            $('#editModal .modal-title').text("Details");
            $('#editModal').modal('show');
            $('#editModal form input,#editModal form textarea,#editModal form select, .save_dt_btn').prop('disabled', true);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching modal content: ' + error);
        }
    });
}

$(document).on('click', '.add_dt_btn', function(e){
    var url = $(this).attr('data-url');
    openAddModal(url);
});

function openAddModal(url)
{
    // Make an AJAX request to fetch the modal content
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            // Update the modal content with the fetched content
            $('#editModal .modal-form-body').html(response);
            $('.selectpicker').selectpicker('refresh');
            // Show the edit modal
            $('#editModal .modal-title').text("Add New");
            $('#editModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching modal content: ' + error);
        }
    });
}

function dtDeleteItem(id, url, tabId)
{
    // Show a confirmation dialog
    Swal.fire({
        title: 'Are you sure you want to delete this record?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            // Call your delete function here
            $.ajax({
                type: "DELETE",
                url: url+'/'+id,
                data: ({_token:mtoken}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.Result,
                        title: 'Employee Details',
                        text: data.Message,
                    });
                    $('#' + tabId + '-table').DataTable().ajax.reload();
                },
            });
        }
    });
}

$(document).on('click', '.save_dt_btn', function(e){
    e.preventDefault();
    let form=$(this).closest('form');
    let data = new FormData(form[0]);
    let url = form.attr("action");
    let method = form.attr("method");

    // Show a confirmation dialog
    Swal.fire({
        title: 'Are you sure you want to save?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Save',
    }).then((result) => {
        if (result.isConfirmed) {
            // Call your delete function here
            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    html = '<div class="alert alert-danger">';
                    for (control in XMLHttpRequest.responseJSON.errors) {
                        html += '<p>' + XMLHttpRequest.responseJSON.errors[control] + '</p>';
                    }
                    html += '</div>';
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    $('#form_result').html(html).slideDown(300).delay(10000).slideUp(300);
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.Result,
                        title: 'Employee Details',
                        text: data.Message,
                    });
                    if(data.Result ==="success"){
                        var gh = sessionStorage.getItem('tabId');
                        $('#' + gh + '-table').DataTable().ajax.reload();
                        $('#editModal').modal('hide');
                    }
                },
            });
        }
    });
});

function removeQueryParamsFromUrl(url) {
    // Use the URL constructor to parse the URL
    const parsedUrl = new URL(url);

    // Remove the query parameters by setting the search property to an empty string
    parsedUrl.search = '';

    // Return the updated URL
    return parsedUrl.toString();
}
