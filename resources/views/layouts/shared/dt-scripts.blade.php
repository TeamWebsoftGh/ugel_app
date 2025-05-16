<!--datatable js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>


<script src="/assets/js/pages/datatables-init.js"></script>
<script src="/js/pages/dt-crud.js"></script>
<script>
    // Get display date format from Laravel (e.g., 'DD-MM-YYYY' or 'MM/DD/YYYY')
    let displayDateFormat = "DD-MM-YYYY";
    let backendDateFormat = "YYYY-MM-DD"; // Always send 'YYYY-MM-DD' to backend

    // Default Date Range (Start of Year to Today)
    let defaultStartDate = moment().startOf('year');
    let defaultEndDate = moment();

    // Initialize Date Range Picker
    $('#date_range').daterangepicker({
        startDate: defaultStartDate,
        endDate: defaultEndDate,
        autoUpdateInput: true,
        showDropdowns: true,
        alwaysShowCalendars: true,
        locale: {
            format: displayDateFormat,  // Display format from .env
            separator: " - ",
            cancelLabel: 'Clear',
            applyLabel: "Apply",
            customRangeLabel: "Custom Range"
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment()]
        }
    });

    // Set default backend values in hidden inputs
    $('#filter_start_date').val(defaultStartDate.format(backendDateFormat));
    $('#filter_end_date').val(defaultEndDate.format(backendDateFormat));

    // Handle Date Selection (Apply Selected Dates)
    $('#date_range').on('apply.daterangepicker', function (ev, picker) {
        // Save values in backend format
        $('#filter_start_date').val(picker.startDate.format(backendDateFormat));
        $('#filter_end_date').val(picker.endDate.format(backendDateFormat));

        // Display values in chosen format
        $(this).val(picker.startDate.format(displayDateFormat) + ' - ' + picker.endDate.format(displayDateFormat));

        //table.ajax.reload();
    });

    // Handle Clear Button
    $('#date_range').on('cancel.daterangepicker', function () {
        $(this).val('');
        $('#filter_start_date').val('');
        $('#filter_end_date').val('');
        //table.ajax.reload();
    });


</script>
