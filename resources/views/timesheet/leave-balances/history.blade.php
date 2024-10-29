@extends('layouts.main')
@section('title', 'Employee Leave Balance')
@section('page-title', 'Employee Leaves')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('leaves.index')}}">Employee Leaves</a></li>
@endsection

@section('content')
    <section>
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header with-border">
                    <h3 class="card-title"> {{__('Filter Leave Balance')}} </h3>
                </div>
                <span id="bulk_payment_result"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="GET" id="filter_form" class="form-horizontal" >
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Leave Types</label>
                                            <input type="hidden" name="leave_type_hidden" value="{{ $leave_type->id }}"/>
                                            <select class="form-control selectpicker default_dept" name="filter_leave_type" id="leave_type_id" data-placeholder="Leave Types" required tabindex="-1" aria-hidden="true">
                                                @foreach($leave_types as $leave_type)
                                                    <option value="{{$leave_type->id}}">{{$leave_type->leave_type_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Leave Year</label>
                                            <input type="hidden" name="leave_year_hidden" value="{{ $leave_year }}"/>
                                            <select class="form-control selectpicker default_pay" name="filter_leave_year" id="leave_year" data-placeholder="leave_year" required tabindex="-1" aria-hidden="true">
                                                @forelse($leave_years as $year)
                                                    <option value="{{$year}}" @if($year == date('Y')) selected @endif>{{$year}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-actions">
                                        <button id="leave_balance_filter" type="submit" class="filtering btn btn-primary"> <i class="fa fa-search"></i> {{trans('file.Search')}} </button>
                                        <button id="update_leave_balance" type="submit" class="filtering btn btn-primary"> <i class="fa fa-check-square-o"></i> {{__('Update Leave Balances')}} </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-title text-center"><h3>{{__('Leave Balances')}} <span id="details_month_year"></span></h3></div>
        <div class="container-fluid"><span id="general_result"></span></div>
        <div class="table-responsive">
            <table id="leave_balance-table" class="table ">
                <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{__('Leave Type')}}</th>
                    <th>{{trans('file.Employee')}}</th>
                    <th>{{trans('file.Department')}}</th>
                    <th>{{__('Leave Year')}}</th>
                    <th>{{__('Total Leave')}}</th>
                    <th>{{__('Total Spent')}}</th>
                    <th>{{__('Balance')}}</th>
                </tr>
                </thead>

            </table>
        </div>
    </section>

    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{trans('file.Confirmation')}}</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h4 align="center">{{__('Are you sure you want to remove this data?')}}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">{{trans('file.OK')}}'
                    </button>
                    <button type="button" class="close btn-default"
                            data-dismiss="modal">{{trans('file.Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#leave_type_id').selectpicker('val', $('input[name="leave_type_hidden"]').val());
        $('#leave_year').selectpicker('val', $('input[name="leave_year_hidden"]').val());
        (function($) {
            "use strict";
            fill_datatable();
            function fill_datatable(leave_year = '', leave_type = '', show_past_emp= '') {
                let table_table = $('#leave_balance-table').DataTable({
                    initComplete: function () {
                        this.api().columns([1]).every(function () {
                            var column = this;
                            var select = $('<select><option value=""></option></select>')
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
                                $('select').selectpicker('refresh');
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
                        url: "{{ route('leaves-balance.index') }}",
                        data: {
                            leave_year: leave_year,
                            show_past_emp: show_past_emp,
                            leave_type: leave_type,
                            "_token": "{{ csrf_token()}}"
                        }
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'leave_type',
                            name: 'leave_type',
                        },
                        {
                            data: 'employee',
                            name: 'employee',
                        },
                        {
                            data: 'department',
                            name: 'department',
                        },
                        {
                            data: 'leave_year',
                            name: 'leave_year',
                        },
                        {
                            data: 'total_days',
                            name: 'total_days',
                        },
                        {
                            data: 'spent_days',
                            name: 'spent_days',
                        },
                        {
                            data: 'outstanding_days',
                            name: 'outstanding_days',
                        }
                    ],
                    "order": [],
                    'language': {
                        'lengthMenu': '_MENU_ {{__("records per page")}}',
                        "info": '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
                        "search": '{{trans("file.Search")}}',
                        'paginate': {
                            'previous': '{{trans("file.Previous")}}',
                            'next': '{{trans("file.Next")}}'
                        }
                    },
                    'columnDefs': [
                        {
                            "orderable": false,
                            'targets': [0, 6],
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
                        }
                    ],


                    'select': {style: 'multi', selector: 'td:first-child'},
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
                new $.fn.dataTable.FixedHeader(table_table);
            }

            $('#filter_form').on('submit',function (e) {
                e.preventDefault();
                let leave_year = $('#leave_year').val();
                let leave_type = $('#leave_type_id').val();
                let show_past_emp = $('#show_past_emp').val();
                $('#leave_balance-table').DataTable().destroy();
                fill_datatable(leave_year, leave_type,show_past_emp);
            });

            $(document).ready(function () {

                let date = $('.date');
                date.datepicker({
                    format: "yyyy",
                    startView: "years",
                    minViewMode: 1,
                    autoclose: true,
                }).datepicker("setDate", new Date());
            });

            $('#sample_form').on('submit', function (event) {
                event.preventDefault();
                if ($('#action').val() === '{{trans('file.Add')}}') {

                    let start_date = $("#start_date").datepicker('getDate');
                    let end_date = $("#end_date").datepicker('getDate');
                    let dayDiff = Math.ceil((end_date - start_date) / (1000 * 60 * 60 * 24)) + 1;

                    $('#diff_date_hidden').val(dayDiff);

                    //console.log(dayDiff);
                    $.ajax({
                        url: "{{ route('leaves.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            let html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (let count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }

                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success + '</div>';
                                $('#sample_form')[0].reset();
                                $('select').selectpicker('refresh');
                                $('.date').datepicker('update');
                                $('#leave-table').DataTable().ajax.reload();
                            }
                            $('#form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }
                    })
                }
            });

            $('#close').on('click', function () {
                $('#sample_form')[0].reset();
                $('select').selectpicker('refresh');
                $('.date').datepicker('update');
                $('#leave_balance-table').DataTable().ajax.reload();
            });
            $('#update_leave_balance').on('click', function(event) {
                event.preventDefault();
                let leave_year = $('#leave_year').val();
                let leave_type = $('#leave_type_id').val();
                let show_past_emp = $('#show_past_emp').val();
                console.log(leave_year);
                console.log(leave_type);

                let target = '{{route('leaves-balance.update')}}' ;
                bootbox.confirm("<h4>UPDATE</h4><hr /><div>This action that will update <b> LEAVE BALANCE </b>. Are you sure you want to <b><span style='color:green'> update </span> LEAVE BALANCE</b>?</div>", function (result) {
                    if (result === true) {
                        $.ajax({
                            url: target,
                            method: "POST",
                            data : {leave_year:leave_year, leave_type:leave_type},
                            // data: new FormData(document.getElementById("filter_form")),
                            // contentType: false,
                            // cache: false,
                            // processData: false,
                            // dataType: "json",
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                            },
                            success: function (data) {
                                bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                                    if(data.Result === "SUCCESS")
                                    {
                                        $('#leave_balance-table').DataTable().ajax.reload();
                                    }
                                });
                            },
                        });
                    }
                });

            });
        })(jQuery);
    </script>

@endsection
