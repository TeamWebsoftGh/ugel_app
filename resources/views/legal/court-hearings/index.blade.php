@extends('layouts.main')
@section('title', 'Court Hearings')
@section('page-title', 'Court Hearings')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="booking_period-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Case Number</th>
                            <th>Case Title</th>
                            <th>Venue</th>
                            <th>Judge</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="not-exported">Action</th>
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
        let baseUrl = '/property/amenities/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'case_number', name: 'case_number'},
                        {data: 'case_title', name: 'case_title'},
                        {data: 'venue', name: 'venue'},
                        {data: 'judge', name: 'judge'},
                        {data: 'status', name: 'status'},
                        {data: 'date', name: 'date'},
                        {data: 'time', name: 'time'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("booking_period", "{{ route('court-hearings.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

