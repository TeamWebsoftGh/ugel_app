@extends('layouts.main')
@section('title', 'List of Past Employees')
@section('page-title', 'Past Employees')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Filter
                    </h4>
                </div>
                <div class="card-body">
                    @include('employee.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Past Employees
                    </h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <table id="employee-table" class="table ">
                            <thead>
                            <tr>
                                <th scope="col" class="not_exported"></th>
                                <th>{{trans_choice('employees.employees', 1)}}</th>
                                <th>{{trans_choice('general.companies', 1)}}</th>
                                <th>{{trans_choice('general.contacts', 2)}}</th>
                                <th class="not-exported">{{trans('general.actions')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("js")
    @include('layouts.shared.dt-scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true
            });
            let otable = $('#employee-table').DataTable({
                lengthChange:!1,
                responsive: true,
                pageLength: 25,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('past-employees.index') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_subsidiary  = $("#filter_subsidiary").val();
                        d.filter_department  = $('#filter_department').val();
                        d.filter_designation = $('#filter_designation').val();
                        d.filter_office_shift = $('#filter_office_shift').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'company',
                        name: 'company',
                    },
                    {
                        data: 'contacts',
                        name: 'contacts',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
                "order": [],
                'language': {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                'columnDefs': [
                    {
                        "orderable": false,
                        'targets': [0],
                    },
                    {
                        'render': function (data, type, row, meta) {
                            if (type === 'display') {
                                data = '<div class="form-check"><input class="form-check-input fs-15" type="checkbox"></div>';
                            }
                            return data;
                        },
                        'checkboxes': {
                            'selectRow': true,
                            'selectAllRender': '<div class="form-check"><input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option"></div>'
                        },
                        'targets': [0]
                    }
                ],
                'select': {style: 'multi', selector: 'td:first-child'},
                'lengthMenu': [[25, 50, -1], [25, 50, "All"]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'colvis',
                        text: '<i title="column visibility" class="fa fa-eye"></i>',
                        columns: ':gt(0)'
                    },
                ],
            });
            new $.fn.dataTable.FixedHeader(otable);
            // otable.buttons().container()
            //     .appendTo( $('.col-sm-6:eq(0)', otable.table().container() ) );

            $(".checkAll").on( "click", function(e) {
                if ($(this).is( ":checked" )) {
                    otable.rows().select();
                } else {
                    otable.rows().deselect();
                }
            });
        });

        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let dependent = $(this).data('dependent');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#department_id').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });

        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let dependent = $(this).data('shift_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_office_shifts') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#office_shift_id').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });

        $('.designation').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let designation_name = $(this).data('designation_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_designation_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, designation_name: designation_name},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#designation_id').html(result);
                        $('select').selectpicker();

                    }
                });
            }
        });
        //--------  Filter  ---------

        // Company--> Department
        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $('#company_id_filter').val();
                let dependent = $(this).data('dependent');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#department_id_filter').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });

        //Department--> Designation
        $('.designationFilter').change(function () {
            if ($(this).val() !== '') {
                // let value = $(this).val();
                // let value = $('#company_id_filter').val();
                let value = $('#department_id_filter').val();
                let designation_name = $(this).data('designation_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_designation_department') }}",
                    method: "POST",
                    data: {value: value, _token: _token, designation_name: designation_name},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#designation_id_filter').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });
        //Company--> Office Shift
        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                // let value = $(this).val();
                let value = $('#company_id_filter').val();
                let dependent = $(this).data('shift_name');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_office_shifts') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {
                        $('select').selectpicker("destroy");
                        $('#office_shift_id_filter').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });
    </script>
@endsection
