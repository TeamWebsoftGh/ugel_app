@extends('layouts.main')
@section('title', 'Payment Gateways')
@section('page-title', 'Payment Gateways')
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
                    <table id="payment_gateway-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Mode</th>
                            <th>Description</th>
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
        let baseUrl = '/configuration/payment-gateways/' ;
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
                        {data: 'mode', name: 'mode'},
                        {data: 'description', name: 'description'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("payment_gateway", "{{ route('configuration.payment-gateways.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
