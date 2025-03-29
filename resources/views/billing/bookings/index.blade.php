@extends('layouts.main')
@section('title', 'Bookings')
@section('page-title', 'Bookings')
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
                    <table id="booking-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Customer Name</th>
                            <th>Customer Id</th>
                            <th>Booking Type</th>
                            <th>Lease Start Date</th>
                            <th>Lease End Date</th>
                            <th>Status</th>
                            <th>Last Modified</th>
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
                        {data: 'client_name', name: 'client_name'},
                        {data: 'client_number', name: 'client_number'},
                        {data: 'booking_type', name: 'booking_type'},
                        {data: 'lease_start_date', name: 'lease_start_date'},
                        {data: 'lease_end_date', name: 'lease_end_date'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("booking", "{{ route('bookings.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

