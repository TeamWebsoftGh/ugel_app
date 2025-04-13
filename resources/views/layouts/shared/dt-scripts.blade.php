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
    $(function () {
        const displayFormat = 'DD-MM-YYYY';
        const backendFormat = 'YYYY-MM-DD';

        const $dateRange = $('#date_range');
        const $startInput = $('#filter_start_date');
        const $endInput = $('#filter_end_date');

        // Read from data-* attributes
        const startRaw = $dateRange.data('start');
        const endRaw = $dateRange.data('end');

        const startDate = startRaw ? moment(startRaw, backendFormat) : moment().startOf('year');
        const endDate = endRaw ? moment(endRaw, backendFormat) : moment();

        $dateRange.daterangepicker({
            startDate: startDate,
            endDate: endDate,
            showDropdowns: true,
            alwaysShowCalendars: true,
            autoUpdateInput: true,
            locale: {
                format: displayFormat,
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Clear'
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

        // Set default hidden values
        $startInput.val(startDate.format(backendFormat));
        $endInput.val(endDate.format(backendFormat));
        $dateRange.val(startDate.format(displayFormat) + ' - ' + endDate.format(displayFormat));

        // On Apply
        $dateRange.on('apply.daterangepicker', function (ev, picker) {
            $startInput.val(picker.startDate.format(backendFormat));
            $endInput.val(picker.endDate.format(backendFormat));
            $(this).val(picker.startDate.format(displayFormat) + ' - ' + picker.endDate.format(displayFormat));
        });

        // On Cancel
        $dateRange.on('cancel.daterangepicker', function () {
            $(this).val('');
            $startInput.val('');
            $endInput.val('');
        });
    });
</script>
