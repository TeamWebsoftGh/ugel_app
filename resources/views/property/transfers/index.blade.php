@extends('layouts.main')
@section('title', 'List of Employee Transfers')
@section('page-title', 'Employee Transfers')
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
                    <table id="transfer-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Staff Id')}}</th>
                            <th>{{__('Employee Name')}}</th>
                            <th>{{__('Transfer Type')}}</th>
                            <th>{{__('Old Department')}}</th>
                            <th>{{__('New Department')}}</th>
                            <th>{{__('Old Branch')}}</th>
                            <th>{{__('New Branch')}}</th>
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
        let baseUrl = '/property/travels/';
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
                        {data: 'transfer_type', name: 'transfer_type'},
                        {data: 'from_department', name: 'from_department'},
                        {data: 'to_department', name: 'to_department'},
                        {data: 'from_branch_name', name: 'from_branch_name'},
                        {data: 'to_branch_name', name: 'to_branch_name'},
                        {data: 'action', name: 'action', orderable: false}
                    ];
                loadDataAndInitializeDataTable("transfer", "{{ route('property.transfers.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
