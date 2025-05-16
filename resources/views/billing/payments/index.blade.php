@extends('layouts.main')
@section('title', 'Payments')
@section('page-title', 'Payments')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header", ["hide" => 1])
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="payment-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Invoice #</th>
                            <th>Transaction #</th>
                            <th>Payment Gateway</th>
                            <th>Customer Name</th>
                            <th>Customer Number</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th width="100" class="not-exported">Action</th>
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
                        {data: 'invoice_number', name: 'invoice_number'},
                        {data: 'transaction_id', name: 'transaction_id'},
                        {data: 'payment_gateway_name', name: 'payment_gateway_name'},
                        {data: 'client_name', name: 'client_name'},
                        {data: 'client_number', name: 'client_number'},
                        {data: 'formatted_total', name: 'formatted_total'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("payment", "{{ route('payments.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

