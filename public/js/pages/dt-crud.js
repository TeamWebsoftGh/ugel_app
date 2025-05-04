const token = $('meta[name="csrf-token"]').attr('content');

function loadDataAndInitializeDataTable(tabId, url, columns, filterContainerSelector = '#filter_form', enableDeleteSelectedButton = true) {
    const $table = $('#' + tabId + '-table');

    // Check if the first column is a checkbox selector
    const hasCheckboxColumn = columns.length &&
        columns[0].data === null &&
        columns[0].orderable === false &&
        columns[0].searchable === false;

    const columnDefs = [];

    if (hasCheckboxColumn) {
        columnDefs.push(
            {
                orderable: false,
                targets: [0]
            },
            {
                render: function (data, type) {
                    if (type === 'display') {
                        return '<div class="form-check"><input type="checkbox" class="form-check-input fs-15"><label></label></div>';
                    }
                    return data;
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender: '<div class="form-check"><input type="checkbox" class="form-check-input fs-15"><label></label></div>'
                },
                targets: [0]
            }
        );
    }

    const table = $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET',
            data: function (d) {
                if (filterContainerSelector) {
                    $.extend(d, getFilterData(filterContainerSelector));
                }
            }
        },
        columns: columns,
        order: [],
        pageLength: 25,
        lengthChange: true,
        lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
        select: hasCheckboxColumn ? { style: 'multi' } : false,
        columnDefs: columnDefs,
        initComplete: function () {
            $table.on('click', 'tr .dt-edit', function () {
                openEditModal(tabId, url, $(this).data('id'));
            });

            $table.on('click', 'tr .dt-show', function () {
                openShowModal(tabId, url, $(this).data('id'));
            });

            $table.on('click', 'tr .dt-delete', function () {
                dtDeleteItem($(this).data('id'), url, tabId);
            });

            if (hasCheckboxColumn) {
                table.on('select', function (e, dt, type, indexes) {
                    toggleDeleteButton(table);
                    updateRowClasses(table, indexes, true);
                    updateSelectAllCheckbox(table);
                });

                table.on('deselect', function (e, dt, type, indexes) {
                    toggleDeleteButton(table);
                    updateRowClasses(table, indexes, false);
                    updateSelectAllCheckbox(table);
                });

                $table.on('click', 'thead input[type="checkbox"]', function () {
                    this.checked ? table.rows().select() : table.rows().deselect();
                });

                if (enableDeleteSelectedButton) {
                    addDeleteSelectedButton(tabId, table, url);
                }
            }
        },
        dom: '<"top"lB>frtip',
        buttons: ['pdf', 'csv', 'excel', 'print'].map(format => ({
            extend: format,
            exportOptions: {
                columns: ':visible:not(.not-exported)',
                rows: ':visible'
            }
        }))
    });
}

function toggleDeleteButton(table) {
    $('#delete-selected-button').toggle(table.rows({ selected: true }).count() > 0);
}

function updateRowClasses(table, indexes, isSelected) {
    indexes.forEach(index => {
        const $row = $(table.row(index).node());
        $row.toggleClass('item-details', isSelected);
        $row.find('input[type="checkbox"]').prop('checked', isSelected);
    });
}

function addDeleteSelectedButton(tabId, table, url) {
    const $button = $('<button id="delete-selected-button" class="btn btn-danger" style="display:none;">Delete Selected</button>');
    $button.on('click', () => {
        const rows = table.rows({ selected: true }).data();
        const ids = [];
        rows.each(row => ids.push(row.id));
        ids.length ? dtMultiDeleteItem(ids, url, tabId) : alert('No rows selected!');
    });
    $('#' + tabId + '-table_wrapper .dt-buttons').prepend($button);
}

function openEditModal(tabId, url, id) {
    const modalUrl = removeQueryParamsFromUrl(url) + '/' + id + '/edit';
    $.get(modalUrl, function (response) {
        $('#editModal .modal-form-body').html(response);
        initModalUI("Edit Record");
    }).fail(() => console.error('Error loading edit modal.'));
}

function openShowModal(tabId, url, id) {
    const modalUrl = removeQueryParamsFromUrl(url) + '/' + id + '/edit';
    $.get(modalUrl, function (response) {
        $('#editModal .modal-form-body').html(response);
        initModalUI("Details", true);
    }).fail(() => alert('Page failed to load.'));
}

function initModalUI(title, disableInputs = false) {
    $('#editModal .modal-title').text(title);
    $('#editModal').modal('show');
    $('.selectpicker').selectpicker('refresh');
    $('.summernote').summernote({ height: 150 }).summernote('enable');
    if (disableInputs) {
        $('#editModal form').find('input, textarea, select, .save_dt_btn, .save_btn').prop('disabled', true);
        $('.save_dt_btn, .save_btn, .hide_show').hide();
    }
}

function openAddModal(url) {
    $.get(url, function (response) {
        $('#editModal .modal-form-body').html(response);
        initModalUI("Add New");
    }).fail(() => alert('Page failed to load.'));
}

function dtDeleteItem(id, url, tabId) {
    Swal.fire({
        title: 'Delete this record?',
        text: 'You won\'t be able to revert this!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: `${url}/${id}`,
                data: { _token: token },
                success: res => {
                    Swal.fire('', res.message, res.status.toLowerCase());
                    reloadDataTable(tabId);
                },
                error: HandleJSONPOSTErrors
            });
        }
    });
}

function dtMultiDeleteItem(ids, url, tabId) {
    Swal.fire({
        title: `Delete ${ids.length} record(s)?`,
        text: 'This cannot be undone!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: `${url}/delete/selected`,
                data: { ids, _token: token },
                success: res => {
                    Swal.fire('', res.message, res.status.toLowerCase());
                    reloadDataTable(tabId);
                },
                error: HandleJSONPOSTErrors
            });
        }
    });
}

$(document).on('click', '.add_dt_btn', function () {
    openAddModal($(this).data('url'));
});

$(document).on('click', '.filter_submit', function () {
    $('.table').each(function () {
        $(this).DataTable().ajax.reload();
    });
});

$(document).on('click', '.save_dt_btn', function (e) {
    e.preventDefault();
    const $form = $(this).closest('form');
    const formData = new FormData($form[0]);

    Swal.fire({
        title: 'Save changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Save'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: res => {
                    Swal.fire('', res.message, res.status.toLowerCase());
                    if (res.status === 'success') {
                        reloadDataTable($form.attr('id'));
                        $('#editModal').modal('hide');
                    }
                    $('span.text-danger').empty();
                },
                error: function (xhr) {
                    HandleJSONPOSTErrors(xhr);
                    if (xhr.responseJSON?.errors) {
                        for (let field in xhr.responseJSON.errors) {
                            $('#error-' + field).html(xhr.responseJSON.errors[field]);
                        }
                    }
                }
            });
        }
    });
});

function reloadDataTable(tabId) {
    $('#' + tabId + '-table').DataTable().ajax.reload();
}

function removeQueryParamsFromUrl(url) {
    return new URL(url).origin + new URL(url).pathname;
}

function updateSelectAllCheckbox(table) {
    const headerCheckbox = $('thead input[type="checkbox"]');
    const totalRows = table.rows({ search: 'applied' }).count();
    const selectedRows = table.rows({ selected: true }).count();
    headerCheckbox.prop('checked', totalRows === selectedRows && totalRows > 0);
}

function getFilterData(containerSelector = '#filter_form') {
    const data = {};
    $(containerSelector).find('input[name], select[name], textarea[name]').each(function () {
        const $el = $(this);
        if (($el.is(':checkbox') || $el.is(':radio')) && !$el.is(':checked')) return;
        data[$el.attr('name')] = $el.val();
    });
    return data;
}
