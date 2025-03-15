@extends('layouts.main')
@section('title', 'My Requests')
@section('page-title', 'Employee Requests')
{{--@section('breadcrumb')--}}
{{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee Requests</a></li>--}}
{{--@endsection--}}
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
                            <th>Request Type</th>
                            <th>Approver Name</th>
                            <th>Staff Id</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Request Date</th>
{{--                            <th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
    @include("layouts.shared.datatable")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                let oTable = $('#employee_request-table').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('employee-requests.my-requests') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {
                            data: 'request_type',
                            name: 'request_type'
                        },
                        {
                            data: 'implementor_name',
                            name: 'implementor_name'
                        },
                        {
                            data: 'staff_id',
                            name: 'staff_id',
                        },
                        {
                            data: 'department',
                            name: 'department'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'request_date',
                            name: 'request_date'
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     orderable: false
                        // }
                    ],
                    'aasorting':-1,
                    'paging': false,
                    "bInfo": false,
                    "order": [0, "asc"],
                    scrollY: 480,
                });
            });
        })(jQuery);
    </script>
@endsection
