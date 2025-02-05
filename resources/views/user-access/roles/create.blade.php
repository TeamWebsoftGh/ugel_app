@extends('layouts.main')
@section('title', 'Manage Roles')
@section('page-title', 'User Access')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('admin.roles.index')}}">Roles</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of User Roles</h4>
                    <p class="card-subtitle mb-4">
                    </p>
                    <div class="">
                        <table id="mini-datab" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card body-->
                <div class="card-footer bg-white">
                    <input type="search" class="form-control" id="filtercolumn_name" name="name">
                </div>
            </div> <!-- end card -->
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Role Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="list-content">
                        @include("user-access.roles.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row-->
@endsection
@section('js')
    @include("layouts.shared.datatable")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                let oTable = $('#mini-datab').DataTable({
                    responsive: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.roles.index') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {
                            data: 'display_name',
                            name: 'display_name',
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
                $("#mini-datab_filter").hide();
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
