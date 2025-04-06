@extends('layouts.main')
@section('title', 'Rooms')
@section('page-title', 'Rooms')
@section('breadcrumb')
{{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    @include("property.partials.filter", ["type" => true, 'property' => true, 'unit' => true])
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")

                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="room-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Room</th>
                            <th>Property Unit</th>
                            <th>Property Name</th>
                            <th>Total Beds</th>
                            <th>Floor</th>
                            <th>Status</th>
                            <th>Date Added</th>
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
                        {data: 'room_name', name: 'room_name'},
                        {data: 'property_unit_name', name: 'property_unit_name'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'bed_count', name: 'bed_count'},
                        {data: 'floor', name: 'floor'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("room", "{{ route('rooms.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
