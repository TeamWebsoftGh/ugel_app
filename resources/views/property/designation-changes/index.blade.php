@extends('layouts.main')
@section('title', 'Employee Designation Changes')
@section('page-title', 'Designation Changes')
@section('breadcrumb')
@endsection

@section('content')
    @can("read-offers")
        @include('property.partials.filter')
    @endcan
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="designation_change-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Staff Id')}}</th>
                            <th>{{__('Employee Name')}}</th>
                            <th>{{__('Old Designation')}}</th>
                            <th>{{__('New Designation')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Description')}}</th>
                            <th class="not-exported">{{__('Action')}}</th>
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
        let baseUrl = '/property/designation-changes/';
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'staff_id', name: 'staff_id'},
                        {data: 'employee_name', name: 'employee_name'},
                        {data: 'old_designation_name', name: 'old_designation_name'},
                        {data: 'new_designation_name', name: 'new_designation_name'},
                        {data: 'change_date', name: 'change_date'},
                        {data: 'description', name: 'description'},
                        {data: 'action', name: 'action', orderable: false}
                    ];
                loadDataAndInitializeDataTable("designation_change", "{{ route('property.designation-changes.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
