@extends('layouts.main')
@section('title', 'Exited Employee List')
@section('page-title', 'Exited Employees')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header", ["import" => 1])
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="termination-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Id</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Exit Type</th>
                            <th>Notice Date</th>
                            <th>Date</th>
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
        let baseUrl = '/property/awards/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'staff_id', name: 'staff_id',},
                        {data: 'terminated_employee', name: 'terminated_employee'},
                        {data: 'department_name', name: 'department_name'},
                        {data: 'exit_type', name: 'exit_type'},
                        {data: 'notice_date', name: 'notice_date'},
                        {data: 'termination_date', name: 'termination_date'},
                        { data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("termination", "{{ route('property.employee-exits.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
