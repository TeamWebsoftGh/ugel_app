@extends('layouts.main')
@section('title', 'Property Units')
@section('page-title', 'Properties')
@section('breadcrumb')
{{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    @include("property.partials.filter", ["type" => true])
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="property_unit-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Unit Name</th>
                            <th>Property Name</th>
                            <th>Property Type</th>
                            <th>Rent Type</th>
                            <th>Rent Amount</th>
                            <th>Last Modified</th>
                            <th width="100px" class="not-exported">Action</th>
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
                        {data: 'unit_name', name: 'unit_name'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'property_type_name', name: 'property_type_name'},
                        {data: 'rent_type', name: 'rent_type'},
                        {data: 'formatted_amount', name: 'formatted_amount'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("property_unit", "{{ route('property-units.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
