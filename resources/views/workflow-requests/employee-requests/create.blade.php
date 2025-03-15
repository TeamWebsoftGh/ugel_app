@extends('layout.admin.main')
@section('title', 'List of Employee Awards')
@section('page-title', 'Employee Awards')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-body">
{{--                    <h4 class="card-title">@yield('title')</h4>--}}
                    <p class="card-subtitle mb-4"></p>
                    <table id="mini-award" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Id</th>
                            <th>Name</th>
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
                    <h4 class="card-title">Award Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="award-content">
                        @include("hrm.award.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/awards/' ;
    </script>
    @include("layout.admin.shared.datatable")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                let oTable = $('#mini-award').DataTable({
                    responsive: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('awards.index') }}",
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'staff_id',
                            name: 'staff_id',
                        },
                        {
                            data: 'employee',
                            name: 'employee'
                        }
                    ],
                    'aasorting':-1,
                    'paging': false,
                    "bInfo": false,
                    "order": [0, "asc"],
                    scrollY: 480,
                    createdRow: function ( row, data, index ) {
                        $(row).addClass('item-details')
                    },
                    'columnDefs': [
                        {
                            "orderable": false,
                            // 'targets': [0,1,9]
                            'targets': [0]
                        },
                        {
                            'render': function (data, type, row, meta) {
                                if (type === 'display') {
                                    data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                                }

                                return data;
                            },
                            'checkboxes': {
                                'selectRow': true,
                                'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                            },
                            'targets': [0]
                        }
                    ],
                    'select': {style: 'multi', selector: 'td:first-child'},
                });
                $("#mini-award_filter").hide();
                $('#filtercolumn_name').on('keyup', function () {
                    oTable.columns(1).search(this.value).draw();
                });
            });
        })(jQuery);
    </script>
@endsection
