@extends('layouts.main')

@section('title', 'Assigned Support Tickets')
@section('page-title', 'Assigned Support Tickets')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">Filter</h4>
                </div>
                <div class="card-body">
                    @include('customer-service.support-tickets.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include("layouts.partials.dt-header")
                    <table id="support_ticket-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Responsible</th>
                            <th>Created By</th>
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
                        {data: null, orderable: false, searchable: false},
                        {data: 'ticket_code', name: 'ticket_code'},
                        {data: 'subject', name: 'subject'},
                        {data: 'client_name', name: 'client_name'},
                        {data: 'assignee_names', name: 'assignee_names'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'status', name: 'status'},
                        {data: 'date_created', name: 'date_created'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("support_ticket", "{{ route('support-tickets.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
