@extends('layouts.main')

@section('title', 'Workflow Positions')
@section('page-title', 'Workflow Positions')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <table id="position-table" class="table dt-responsive">
                        <thead>
                        <tr>
                            <th class="not-exported">
                                <div class="form-check"><input type="checkbox" class="form-check-input fs-15 select-all"><label></label></div>
                            </th>
                            <th>Name</th>
                            <th>Position Type</th>
                            <th>Position Category</th>
                            <th>User</th>
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
                        { data: "position_name" },
                        { data: "workflow_position_type_name" },
                        { data: "category" },
                        { data: "employee_name" },
                        { data: "updated_at" },
                        { data: "action", orderable: false, searchable: false }
                    ];
                loadDataAndInitializeDataTable("position", "{{ route('workflows.positions.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
