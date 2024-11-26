@extends('layouts.main')
@section('title', 'Property Lease List')
@section('page-title', 'Property Leases')
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
                    <table id="property_lease-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Short Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Last Modified</th>
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
                        {data: 'name', name: 'name'},
                        {data: 'short_name', name: 'short_name'},
                        {data: 'category', name: 'category'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("property_type", "{{ route('property-leases.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
