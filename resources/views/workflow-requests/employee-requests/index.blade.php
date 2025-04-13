@extends('layouts.main')
@section('title', 'Employee requests')
@section('page-title', 'Employee requests')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav nav-tabs vertical wf_type" id="myTab" role="tablist">
                        @forelse(get_workflow_types() as $index => $type)
                            <li class="nav-item">
                                <a class="nav-link @if($index == 0)active @endif" id="{{$type->code}}-tab" data-toggle="tab" href="#{{$type->code}}"
                                   role="tab" aria-controls="{{$type->code}}" data-table="{{$type->code}}"
                                   aria-selected="true">{{__($type->name)}} <span class="badge badge-danger badge-sm">{{$type->count ?? 0}}</span></a>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="myTabContent">
                        <span id="form_result"></span>
                        @forelse(get_workflow_types() as $index => $type)
                            <div class="tab-pane fade @if($index == 0)show active @endif" id="{{$type->code}}" role="tabpanel"
                                 aria-labelledby="{{$type->code}}-tab">
                                <!--Contents for Basic starts here-->
                                {{__($type->name)}} Request
                                <hr>
                                <table id="{{$type->code}}-table" class="table" style="box-shadow: none">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Id</th>
                                        <th>Employee Name</th>
                                        <th>Client Name</th>
                                        <th>Status</th>
                                        <th>Request Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Id</th>
                                        <th>Employee Name</th>
                                        <th>Client Name</th>
                                        <th>Status</th>
                                        <th>Request Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include("layouts.shared.datatable")
    <script type="text/javascript">
        $(document).ready(function ()
        {
            let date = $('.date');
            date.datepicker({
                format: '{{ env('Date_Format_JS')}}',
                autoclose: true,
                todayHighlight: true
            });
        });
        setTimeout(()=>{$('[data-table="employee-leave"]').trigger('click');},1000);
        @forelse(get_workflow_types() as $index => $type)
        $('[data-table="{{$type->code}}"]').one('click', function (e) {
            $('#{{$type->code}}-table').DataTable().clear().destroy();
            var table_table = $('#{{$type->code}}-table').DataTable({
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('employee-requests.index',['type' => $type->code]) }}",
                },

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'staff_id',
                        name: 'staff_id',
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'request_date',
                        name: 'request_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
                'aasorting':-1,
                'paging': false,
                "bInfo": false,
                "order": [0, "asc"],
                scrollY: 320,
            });
            new $.fn.dataTable.FixedHeader(table_table);
        });
        @empty
        @endforelse
    </script>
@endsection
