@extends('layouts.main')

@section('title', 'Workflows')
@section('page-title', 'Workflows')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <table id="workflow-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Workflow Type</th>
                            <th>Approver</th>
                            <th>Flow Sequence</th>
                            <th>Last Modified</th>
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
                        { data: "workflow_name" },
                        { data: "workflow_type_name" },
                        { data: "workflow_position_name" },
                        { data: "flow_sequence" },
                        { data: "updated_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("workflow", "{{ route('workflows.workflows.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
