<div class="">
    <table id="task_table" class="table">
        <thead>
        <tr role="row">
            <th>#</th>
            <th>Task#</th>
            <th>Title</th>
            <th>Status</th>
            <th>Date</th>
            @if(!isset($user))
            <th>Responsible</th>
            @endif
            <th>Weightage</th>
            <th>Created By</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>
@section('js')
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                const fp = flatpickr(".date", {
                    mode: "range",
                    dateFormat: '{{ env('Date_Format')}}',
                    autoclose: true,
                    todayHighlight: true,
                    defaultDate: ["{{$data['start_date']}}", "{{$data['end_date']}}"],
                    onChange: function(selectedDates, dateStr, instance) {
                        const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                        $('#filter_start_date').val(dateArr[0])
                        $('#filter_end_date').val(dateArr[1])
                    },
                }); // flatpickr
                let table_table = $('#task_table').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('tasks.index') }}",
                        data: {
                            filter_department: $('#filter_department').val(),
                            filter_status: $('#filter_status').val(),
                            filter_start_date: $('#filter_start_date').val(),
                            filter_end_date: $('#filter_end_date').val(),
                            filter_subsidiary: $('#filter_subsidiary').val(),
                            filter_assignee: $('#filter_assignee').val(),
                        }
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'code',
                            name: 'code',
                        },

                        {
                            data: 'title',
                            name: 'title',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'task_date',
                            name: 'task_date',
                        },
                        {
                            data: 'employee',
                            name: 'employee',
                        },
                        {
                            data: 'weightage',
                            name: 'weightage',
                        },
                        {
                            data: 'user',
                            name: 'user',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ],
                    "order": [],
                    'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    dom: '<"row"lfB>rtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                            exportOptions: {
                                columns: ':visible:Not(.not-exported)',
                                rows: ':visible'
                            },
                        },
                        {
                            extend: 'csv',
                            text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                            exportOptions: {
                                columns: ':visible:Not(.not-exported)',
                                rows: ':visible'
                            },
                        },
                        {
                            extend: 'print',
                            text: '<i title="print" class="fa fa-print"></i>',
                            exportOptions: {
                                columns: ':visible:Not(.not-exported)',
                                rows: ':visible'
                            },
                        },
                        {
                            extend: 'colvis',
                            text: '<i title="column visibility" class="fa fa-eye"></i>',
                            columns: ':gt(0)'
                        },
                    ],
                });
                $('#filterSubmit').on("click",function(e){
                    $('#task_table').DataTable().draw(true);
                });
            });
        })(jQuery);
    </script>
@endsection


