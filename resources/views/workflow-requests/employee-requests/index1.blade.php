@extends('layout.admin.main')
@section('title', 'List of Employee Requests')
@section('page-title', 'Employee Requests')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee Requests</a></li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@yield('title')</h4>
                    <p class="card-subtitle mb-4"></p>
                    <table id="employee_request-table" class="table" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Id</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Staff Id</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        let baseUrl = '/workflow-requests/' ;
    </script>
    @include("layout.admin.shared.datatable")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                let oTable = $('#employee_request-table').DataTable({
                    initComplete: function () {
                        this.api().columns([2, 3, 4, 5]).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                                // $('select').selectpicker('refresh');
                            });
                        });
                    },
                    responsive: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('employee-requests.index') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {
                            data: 'staff_id',
                            name: 'staff_id',
                        },
                        {
                            data: 'employee_name',
                            name: 'employee_name'
                        },
                        {
                            data: 'department',
                            name: 'department'
                        },
                        {
                            data: 'request_type',
                            name: 'request_type'
                        },
                        {
                            data: 'department',
                            name: 'department'
                        },
                        {
                            data: 'request_date',
                            name: 'request_date'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ],
                    'aasorting':-1,
                    'paging': false,
                    "bInfo": false,
                    "order": [0, "asc"],
                    scrollY: 480,
                });

                $(document).on('click', '#bulk_delete', function () {

                    let id = [];
                    let table = $('#property-types-table').DataTable();
                    id = table.rows({selected: true}).ids().toArray();
                    if (id.length > 0) {
                        if (confirm('{{__('Delete Selection',['key'=>trans('file.Award')])}}')) {
                            $.ajax({
                                url: '{{route('mass_delete_awards')}}',
                                method: 'POST',
                                data: {
                                    ticketIdArray: id
                                },
                                success: function (data) {
                                    let html = '';
                                    if (data.success) {
                                        html = '<div class="alert alert-success">' + data.success + '</div>';
                                    }
                                    if (data.error) {
                                        html = '<div class="alert alert-danger">' + data.error + '</div>';
                                    }
                                    table.ajax.reload();
                                    table.rows('.selected').deselect();
                                    if (data.errors) {
                                        html = '<div class="alert alert-danger">' + data.error + '</div>';
                                    }
                                    $('#general_result').html(html).slideDown(300).delay(5000).slideUp(300);

                                }

                            });
                        }
                    } else {
                        alert('{{__('Please select atleast one checkbox')}}');
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
