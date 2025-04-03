@extends('layouts.main')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

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
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                        <div>
                            @if(user()->can(['create-'.get_permission_name()]))
                                <a class="btn btn-primary ms-auto" href="{{route("support-tickets.create")}}">Add New</a>
                            @endif
                        </div>
                    </div>

                    <table id="support_ticket-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Responsible</th>
                            <th>Date</th>
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
                        {data: 'status', name: 'status'},
                        {data: 'assignee_names', name: 'assignee_names'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'created_by', name: 'created_by'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("support_ticket", "{{ route('support-tickets.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
