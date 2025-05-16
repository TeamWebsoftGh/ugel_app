@extends('layouts.main')

@section('title', 'Workflow Types')
@section('page-title', 'Workflow Types')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <table id="workflow_type-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
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
                        { data: "name" },
                        { data: "code" },
                        { data: "description" },
                        { data: "updated_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("workflow_type", "{{ route('workflows.workflow-types.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
