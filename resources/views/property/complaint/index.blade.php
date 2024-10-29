@extends('layouts.main')
@section('title', 'Employee Complaints List')
@section('page-title', 'Employee Complaints')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="complaint-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Complaint From</th>
                            <th>Department</th>
                            <th>Complaint Against</th>
                            <th>Department</th>
                            <th>Complaint Info</th>
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
        let baseUrl = '/property/complaints/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'complaint_from', name: 'complaint_from'},
                        {data: 'complaint_from_department', name: 'complaint_from_department'},
                        {data: 'complaint_against', name: 'complaint_against'},
                        {data: 'complaint_against_department', name: 'complaint_against_department'},
                        {data: 'complaint_title', name: 'complaint_title'},
                        {data: 'complaint_date', name: 'complaint_date'},
                        { data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("complaint", "{{ route('property.complaints.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
