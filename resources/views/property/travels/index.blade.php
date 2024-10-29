@extends('layouts.main')
@section('title', 'Employee Travel List')
@section('page-title', 'Employee Travels')
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
                    <table id="travel-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Staff Id')}}</th>
                            <th>{{__('Employee Name')}}</th>
                            <th>{{__('Arrangement Type')}}</th>
                            <th>{{__('Purpose of Travel')}}</th>
                            <th>{{__('Place of Visit')}}</th>
                            <th>{{__('Duration')}}</th>
                            <th>{{__('Status')}}</th>
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
                        {data: 'travel_type_name', name: 'travel_type_name'},
                        {data: 'purpose_of_visit', name: 'purpose_of_visit'},
                        {data: 'place_of_visit', name: 'place_of_visit'},
                        {data: 'duration', name: 'duration'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false}
                    ];
                loadDataAndInitializeDataTable("travel", "{{ route('property.travels.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
