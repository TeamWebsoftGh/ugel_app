@extends('layouts.main')
@section('title', 'Manage Workflow Types')
@section('page-title', 'Workflow Types')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('workflows.workflow-types.index')}}">Workflow Types</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-body">
{{--                    <h4 class="card-title">Workflow Positions</h4>--}}
{{--                    <p class="card-subtitle mb-4"></p>--}}
                    <table id="mini-workflow_position" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
                <div class="card-footer bg-white" id="search_form">
                    <input type="search" id="filtercolumn_name" class="form-control" placeholder="search name" name="search_item">
                </div>
            </div> <!-- end card-->
        </div> <!-- end col -->
        <div class="col-md-8 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Workflow Type Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="wf_position-content">
                        @include("workflow.workflow-types.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/pay_deductions/' ;
    </script>
    @include("layouts.shared.datatable")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                let oTable = $('#mini-workflow_position').DataTable({
                    responsive: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('workflows.workflow-types.index') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function (data) {
                                if (data == '1') {
                                    return "<td><div class = 'badge badge-success'>{{trans('file.Active')}}</div>"
                                } else {
                                    return "<td><div class = 'badge badge-danger'>{{trans('file.Inactive')}}</div>"
                                }
                            }
                        },
                    ],
                    'aasorting':-1,
                    'paging': false,
                    "bInfo": false,
                    "order": [0, "asc"],
                    scrollY: 480,
                    createdRow: function ( row, data, index ) {
                        $(row).addClass('item-details')
                    },
                });
                $("#mini-workflow_position_filter").hide();
                $('#filtercolumn_name').on('keyup', function () {
                    oTable.columns(1).search(this.value).draw();
                });
            });


            $('#toggle').on('click', function (e) {
                e.preventDefault();
                $('#show_hide').toggle();
            });

        })(jQuery);
    </script>
@endsection
