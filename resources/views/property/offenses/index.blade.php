@extends('layouts.main')
@section('title', 'Employee Offense List')
@section('page-title', 'Employee Offenses')
@section('breadcrumb')
@endsection

@section('content')
    @can("read-offenses")
        @include('property.partials.filter')
    @endcan
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="offense-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Id</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Offense Type</th>
                            <th>Offence Date</th>
                            <th>Warning Type</th>
                            <th>Warning Date</th>
                            <th>Action</th>
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
        let baseUrl = '/property/offenses/';
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'staff_id', name: 'staff_id',},
                        {data: 'employee_name', name: 'employee_name'},
                        {data: 'department_name', name: 'department_name'},
                        {data: 'offense_type_name', name: 'offense_type_name'},
                        {data: 'offence_date', name: 'offence_date'},
                        {data: 'warning_type_name', name: 'warning_type_name'},
                        {data: 'warning_date', name: 'warning_date'},
                        {data: 'action', name: 'action', orderable: false}
                    ];
                loadDataAndInitializeDataTable("offense", "{{ route('property.offenses.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
