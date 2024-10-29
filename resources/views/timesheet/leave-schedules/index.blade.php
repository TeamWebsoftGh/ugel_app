@extends('layouts.main')
@section('title', 'Leave Scheduler')
@section('page-title', 'Leave Scheduler')
@section('breadcrumb')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="leave_schedule-table" class="table dt-responsive" style="box-shadow: none">
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
                loadDataAndInitializeDataTable("leave_schedule", "{{ route('timesheet.leave-schedules.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
