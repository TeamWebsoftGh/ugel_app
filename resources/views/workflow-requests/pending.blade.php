@extends('layouts.main')

@section('title', 'Pending Workflow Requests')
@section('page-title', 'Pending Workflow Requests')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                </div>
                <div class="card-body">
                    <table id="workflow_request-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Request Type</th>
                            <th>Requestor Name</th>
                            <th>Stage</th>
                            <th>Implementor</th>
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
                        {data: null, orderable: false, searchable: false},
                        { data: "request_type" },
                        { data: "requester_name" },
                        { data: "stage" },
                        { data: "implementor_name" },
                        { data: "status" },
                        { data: "created_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("workflow_request", "{{ route('workflow-requests.pending') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
