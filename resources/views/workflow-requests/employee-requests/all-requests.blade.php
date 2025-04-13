@extends('layouts.main')
@section('title', 'All Requests')
@section('page-title', 'Employee Requests')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Filter</h4>
                    <form class="form-horizontal" id="filter_form" method="GET">
                        <div class="row mt-3">
                            <div class="col-md-4 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                                <label>{{__('Request Type')}}</label>
                                <select name="filter_request_type" id="filter_request_type" data-live-search="true" class="form-control selectpicker">
                                    <option selected value="">All {{__('Request Types')}}</option>
                                    @foreach($request_types as $request_type)
                                        <option @if($request_type->id == request()->filter_request_type) selected="selected" @endif value="{{ $request_type->id }}">{{ $request_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @yield("filter_form")
                            <div class="col-xl-1 col-lg-1 col-md-4 col-sm-1 col-xs-12 pl-md-3 mt-4">
                                <button type="submit" name="btn" id="filter_form" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0"><i class="fa fa-search"></i> {{__('Search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@yield('title')</h4>
                    <p class="card-subtitle mb-4"></p>
                    <table id="employee_request-table" class="table table-striped" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Request/Stage</th>
                            <th>Employee </th>
                            <th>Approver Name</th>
                            <th>Status</th>
                            <th>Request Date</th>
                           <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request Type</th>
                            <th>Employee</th>
                            <th>Approver Name</th>
                            <th>Status</th>
                            <th>Request Date</th>
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
    @include("layouts.shared.dt-scripts")
    <script type="text/javascript">
        (function($) {
            "use strict";
            $(document).ready(function () {
                fill_datatable();

                function fill_datatable(filter_company = '', filter_department = '', filter_request_type = '', filter_location = '') {
                    let table_table = $('#employee_request-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        paging: true,
                        ajax: {
                            url: "{{ route('employee-requests.all-requests') }}",
                            data: {
                                filter_company: filter_company,
                                filter_department: filter_department,
                                filter_location: filter_location,
                                filter_request_type: filter_request_type,
                                "_token": "{{ csrf_token()}}"
                            }
                        },
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            {
                                data: 'request_detail',
                                name: 'request_detail'
                            },
                            {
                                data: 'employee_name',
                                name: 'employee_name'
                            },
                            {
                                data: 'implementor_name',
                                name: 'implementor_name'
                            },
                            {
                                data: 'status',
                                name: 'status'
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
                        "order": [],
                        'columnDefs': [
                            {
                                "orderable": false,
                                'targets': [0],
                            },
                        ],
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
                $('#filter_form').on('submit',function (e) {
                    e.preventDefault();
                    var filter_company = $('#filter_company_er').val();
                    var filter_category = $('#filter_request_type').val();
                    var filter_location = $('#filter_location').val();
                    var filter_department = $('#filter_department').val();
                    $('#employee_request-table').DataTable().destroy();
                    fill_datatable(filter_company, filter_department, filter_category, filter_location);
                });
            });
        })(jQuery);


        $(document).on('click', '.forward_request', function(e){
            let token = $('meta[name=csrf-token]').attr("content");
            let url = $(this).data('url');
            $.post(url,{_token:token},function(data){
                $('#FormModal').modal('show');
                $('#modal_form_content').html(data);
            });

        })

        $(document).on('click', '.send_forward_request', function(e){
            let x = confirm('Do you want to forward this request?');
            if(x!==true){e.preventDefault();return 0;}else{
                e.preventDefault();
                let $form = $(this).closest('form'),
                    url = $form.attr('action'),
                    employee = $form.find('#employee').val();
                token = $('meta[name=csrf-token]').attr("content"),
                    data = {_token:token,employee:employee} ;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(data){
                        if(data.RESPONSE_TYPE === "SUCCESS")
                        {
                            alert(data.MESSAGE);
                            window.location.reload();
                        }else{
                            alert(data.MESSAGE);
                        }
                    },
                    error: function(data){
                        alert('Error! Unable to process your request. Please try again later.');
                    }
                });
            }
        });

    </script>
@endsection
