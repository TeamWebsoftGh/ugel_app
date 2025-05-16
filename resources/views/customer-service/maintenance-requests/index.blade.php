@extends('layouts.main')

@section('title', 'Maintenance Requests')
@section('page-title', 'Maintenance Requests')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">Filter</h4>
                </div>
                <div class="card-body">
                    @include('customer-service.maintenance-requests.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include("layouts.partials.dt-header")
                    <table id="maintenance_request-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th width="15%">Client Name</th>
                            <th>Client Phone</th>
                            <th>Maintenance Category</th>
                            <th>Property Name</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        { data: "id" },
                        { data: "client_name" },
                        { data: "client_phone" },
                        { data: "category_name" },
                        { data: "property_name" },
                        { data: "priority_name" },
                        { data: "status" },
                        { data: "created_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("maintenance_request", "{{ route('maintenance-requests.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
