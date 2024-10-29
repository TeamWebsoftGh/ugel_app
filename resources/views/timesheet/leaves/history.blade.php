@extends('layouts.main')
@section('title', 'My Leaves')
@section('page-title', 'Employee Leaves')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('timesheet.leaves.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                    <div>
                        <a href="{{route("timesheet.leaves.create")}}" class="btn btn-soft-info ms-auto">Apply for Leave</a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="leave-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Resumption Date</th>
                            <th>Total Days</th>
                            <th>Staff Id</th>
                            <th>Name</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/timesheet/holidays/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'leave_type', name: 'leave_type',},
                        {data: 'start_date', name: 'start_date'},
                        {data: 'end_date', name: 'end_date'},
                        {data: 'resumption_date', name: 'resumption_date'},
                        {data: 'total_days', name: 'total_days'},
                        {data: 'staff_id', name: 'staff_id'},
                        {data: 'employee', name: 'employee'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("leave", "{{ route('timesheet.leaves.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
