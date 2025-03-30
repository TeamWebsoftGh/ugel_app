@extends('layouts.main')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="invoice-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Invoice Number</th>
                            <th>Invoice Date</th>
                            <th>Customer Name</th>
                            <th>Customer Number</th>
                            <th>Property</th>
                            <th>Property Unit</th>
                            <th>Total Amount</th>
                            <th>Total Paid</th>
                            <th>Status</th>
                            <th>Due Date</th>
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
                        {data: 'invoice_date', name: 'invoice_date'},
                        {data: 'client_name', name: 'client_name'},
                        {data: 'client_number', name: 'client_number'},
                        {data: 'property_name', name: 'property_name'},
                        {data: 'property_unit_name', name: 'property_unit_name'},
                        {data: 'formatted_total', name: 'formatted_total'},
                        {data: 'formatted_paid', name: 'formatted_paid'},
                        {data: 'status', name: 'status'},
                        {data: 'due_date', name: 'due_date'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("invoice", "{{ route('invoices.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

