@extends('layouts.main')

@section('title', 'Bulk Sms')
@section('page-title', 'Bulk Sms')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">Filter</h4>
                </div>
                <div class="card-body">
                    @include('communication.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include("layouts.partials.dt-header")
                    <table id="bulk_sms-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th width="15%">Title</th>
                            <th width="25%">Description</th>
                            <th>Property Type</th>
                            <th>Property</th>
                            <th>Client Type</th>
                            <th>Date Added</th>
                            <th class="not-exported">Action</th>
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
                        { data: "title" },
                        { data: "short_message" },
                        { data: "property_type_name" },
                        { data: "property_name" },
                        { data: "client_type_name" },
                        { data: "created_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("bulk_sms", "{{ route('bulk-sms.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
