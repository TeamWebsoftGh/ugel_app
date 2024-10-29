var token = $('meta[name="csrf-token"]').attr('content');

function loadDataAndInitializeDataTable(tabId, url, columns) {
    var table = $('#' + tabId + '-table');

    table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET',
            data: function (d) {
                d.filter_constituency  = $("#filter_constituency").val();
                d.filter_electoral_area  = $('#filter_electoral_area').val();
                d.filter_polling_station = $('#filter_polling_station').val();
                d.filter_region = $('#filter_region').val();
            }
        },
        columns: columns,
        order: [],
        pageLength: 25,
        // buttons:["copy", "print", "pdf", "csv", "excel"],
        createdRow: function ( row, data, index ) {
            $(row).addClass('item-details')
        },
        'columnDefs': [
            {
                "orderable": false,
                // 'targets': [0,1,9]
                'targets': [0]
            },
            {
                'render': function (data, type, row, meta) {
                    if (type === 'display') {
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                    return data;
                },
                'checkboxes': {
                    'selectRow': true,
                    'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            },
        ],
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
                console.log(dataId);
                openShowModal(tabId, url, dataId);
            });
            $(this).on('click', 'tr .dt-delete', function() {
                var dataId = $(this).data('id');
                dtDeleteItem(dataId, url, tabId);
            });
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            }
        ],
    });
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

$(document).on('click', '.filter_submit', function(e){
    var dataTableArray = $('.table').map(function () {
        return $(this).DataTable();
    }).toArray();

// Reload all DataTables
    dataTableArray.forEach(function (dataTable) {
        dataTable.ajax.reload();
    });
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
                data: ({_token:token}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.Result,
                        title: '',
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
    let formId = form.attr("id");
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
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    for (control in XMLHttpRequest.responseJSON.errors) {
                        $('#error-' + control).html(XMLHttpRequest.responseJSON.errors[control]);
                    }
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.Result,
                        title: '',
                        text: data.Message,
                    });
                    if(data.Result ==="success"){
                        $('#' + formId + '-table').DataTable().ajax.reload();
                        $('#editModal').modal('hide');
                    }
                    $('span.text-danger').html('');
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
