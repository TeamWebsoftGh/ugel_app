@extends('layouts.main')
@section('title', 'Currencies')
@section('page-title', 'Manage Currencies')
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
                    <table id="currency-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Symbol</th>
                            <th>Precision</th>
                            <th>Exchange Rate</th>
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
                        {data: 'currency', name: 'currency'},
                        {data: 'code', name: 'code'},
                        {data: 'symbol', name: 'symbol'},
                        {data: 'precision', name: 'precision'},
                        {data: 'exchange_rate', name: 'exchange_rate'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("currency", "{{ route('configuration.currencies.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
