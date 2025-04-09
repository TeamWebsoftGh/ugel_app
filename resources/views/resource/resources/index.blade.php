@extends('layouts.main')
@section('title', 'Resources')
@section('page-title', 'Resources')
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
                    <table id="resource-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Title</th>
                            <th>Category Name</th>
                            <th>Publish Date</th>
                            <th>Publish Date</th>
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
                        {data: 'title', name: 'title'},
                        {data: 'category_name', name: 'category_name'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'client_type_name', name: 'client_type_name'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("resource", "{{ route('resource.resources.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

