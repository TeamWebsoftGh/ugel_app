var token = $('meta[name="csrf-token"]').attr('content');

function loadDataAndInitializeDataTable(tabId, url, columns, filterContainerSelector = '#filter_form') {
    var table = $('#' + tabId + '-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET',
            data: function (d) {
                // Merge filter data from the specified container into the AJAX request
                if (filterContainerSelector) {
                    $.extend(d, getFilterData(filterContainerSelector));
                }
            }
        },
        columns: columns,
        order: [],
        pageLength: 25,
        lengthChange: true,
        lengthMenu: [ [25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],  // Options for page length select
        select: {
            style: 'multi'  // Multiple row selection
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0]
            },
            {
                'render': function (data, type, row, meta) {
                    if (type === 'display') {
                        data = '<div class="form-check"><input type="checkbox" class="form-check-input fs-15"><label></label></div>';
                    }
                    return data;
                },
                'checkboxes': {
                    'selectRow': true,
                    'selectAllRender': '<div class="form-check"><input type="checkbox" class="form-check-input fs-15"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        initComplete: function(settings, json) {
            // Handle row clicks for this DataTable
            $(this).on('click', 'tr .dt-edit', function() {
                var dataId = $(this).data('id');
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

            // Add event listener for row selection (based on checkbox)
            table.on('select', function (e, dt, type, indexes) {
                // Show Delete Selected button when any row is selected
                var selectedRows = table.rows({ selected: true }).count();
                if (selectedRows > 0) {
                    $('#delete-selected-button').show();  // Show Delete button
                }

                indexes.forEach(function(index) {
                    var selectedRow = table.row(index).node();
                    // Add the class 'item-details' to the selected row
                    $(selectedRow).addClass('item-details');

                    // Sync the checkbox with row selection
                    var checkbox = $(selectedRow).find('input[type="checkbox"]');
                    checkbox.prop('checked', true);  // Manually check the checkbox
                });

                // Check if all rows are selected to update the "Select All" checkbox
                updateSelectAllCheckbox(table);
            });

            // Add event listener for row deselection (based on checkbox)
            table.on('deselect', function (e, dt, type, indexes) {
                // Hide Delete Selected button when no rows are selected
                var selectedRows = table.rows({ selected: true }).count();
                if (selectedRows === 0) {
                    $('#delete-selected-button').hide();  // Hide Delete button
                }

                indexes.forEach(function(index) {
                    var deselectedRow = table.row(index).node();
                    // Remove the class 'item-details' when the row is deselected
                    $(deselectedRow).removeClass('item-details');

                    // Sync the checkbox with row deselection
                    var checkbox = $(deselectedRow).find('input[type="checkbox"]');
                    checkbox.prop('checked', false);  // Manually uncheck the checkbox
                });

                // Check if all rows are selected to update the "Select All" checkbox
                updateSelectAllCheckbox(table);
            });

            // Add event listener for Select All checkbox (header checkbox)
            $(this).on('click', 'thead input[type="checkbox"]', function() {
                console.log(table)
                var headerCheckbox = $(this);
                var rows = table.rows({ search: 'applied' }).nodes();  // Get rows based on the search filter

                // If header checkbox is checked, select all rows
                if (headerCheckbox.prop('checked')) {
                    table.rows().select();  // Select all rows
                } else {
                    table.rows().deselect();  // Deselect all rows
                }
            });

            // Add "Delete Selected" button to the DataTable toolbar
            var deleteButton = $('<button id="delete-selected-button" class="btn btn-danger" style="display:none;">Delete Selected</button>');
            deleteButton.on('click', function() {
                var selectedRows = table.rows({ selected: true }).data();
                if (selectedRows.length > 0) {
                    var idsToDelete = [];
                    selectedRows.each(function(value) {
                        idsToDelete.push(value.id);  // Assuming 'id' is the key for each row
                    });
                    // Call function to delete items
                    dtMultiDeleteItem(idsToDelete, url, tabId);
                } else {
                    alert('No rows selected!');
                }
            });
            $('#' + tabId + '-table_wrapper .dt-buttons').prepend(deleteButton);
        },
       // dom: 'Bfrtip',
        dom: '<"top"lB>frtip',
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
            alert("Page failed to load.")
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
            alert("Page failed to load.")
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
        icon: 'question',
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
                        icon: data.status.toLowerCase(),
                        title: '',
                        text: data.message,
                    });
                    $('#' + tabId + '-table').DataTable().ajax.reload();
                },
            });
        }
    });
}

function dtMultiDeleteItem(ids, url, tabId)
{
    // Show a confirmation dialog
    Swal.fire({
        title: 'Are you sure you want to delete '+ids.length+' record(s)?',
        text: 'You won\'t be able to revert this!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            // Call your delete function here
            $.ajax({
                type: "DELETE",
                url: url+'/delete/selected',
                data: {
                    ids: ids,
                    _token: token
                },
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    Swal.fire({
                        icon: data.status.toLowerCase(),
                        title: '',
                        text: data.message,
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
        icon: 'question',
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
                        icon: data.status.toLowerCase(),
                        title: '',
                        text: data.message,
                    });
                    if(data.status ==="success"){
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

// Function to update the "Select All" checkbox state
function updateSelectAllCheckbox(table) {
    var headerCheckbox = $('thead input[type="checkbox"]');
    var totalRows = table.rows({ search: 'applied' }).count();  // Get total rows after search filter
    var selectedRows = table.rows({ selected: true }).count();  // Get selected rows

    // If all rows are selected, check the header checkbox, otherwise uncheck it
    if (totalRows === selectedRows && totalRows > 0) {
        headerCheckbox.prop('checked', true);  // Check the header checkbox
    } else {
        headerCheckbox.prop('checked', false);  // Uncheck the header checkbox
    }
}

function getFilterData(containerSelector = '#filter_form') {
    var filterData = {};
    $(containerSelector).find('input[name], select[name], textarea[name]').each(function() {
        var $this = $(this);
        // For checkboxes and radio buttons, include only if checked
        if (($this.is(':checkbox') || $this.is(':radio')) && !$this.is(':checked')) {
            return;
        }
        filterData[$this.attr('name')] = $this.val();
    });
    return filterData;
}


