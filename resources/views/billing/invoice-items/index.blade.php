@extends('layouts.main')
@section('title', 'Invoice Items')
@section('page-title', 'Invoice Items')
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
                    <table id="invoice_item-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Status</th>
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
                        {data: 'name', name: 'name'},
                        {data: 'price', name: 'price'},
                        {data: 'description', name: 'description'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("invoice_item", "{{ route('invoice-items.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

