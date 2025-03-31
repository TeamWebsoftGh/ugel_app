@extends('layouts.main')
@section('title', 'Reviews')
@section('page-title', 'Reviews')
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
                    <table id="review-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Client Name</th>
                            <th>Client Number</th>
                            <th>Property Name</th>
                            <th>Rating</th>
                            <th>Comment</th>
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
                        {data: 'client_name', name: 'client_name'},
                        {data: 'client_number', name: 'client_number'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'rating', name: 'rating'},
                        {data: 'comment', name: 'comment'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("review", "{{ route('reviews.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
