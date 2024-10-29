@extends('layouts.main')
@section('title', 'Leave Balances')
@section('page-title', 'Employee Leaves')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    @can("read-leaves")
        @include('timesheet.partials.filter')
    @endcan
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                    <div>
                        <button type="button" id="update_leave_balance" class="btn btn-primary ms-auto" data-url="{{route("timesheet.leave-balances.update")}}">Update Leave Balances</button>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="leave_balance-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Leave Type</th>
                            <th>Staff Id</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>{{__('Leave Year')}}</th>
                            <th>{{__('Total Leave')}}</th>
                            <th>{{__('Total Spent')}}</th>
                            <th>{{__('Balance')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/timesheet/holidays/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'leave_type', name: 'leave_type',},
                        {data: 'staff_id', name: 'staff_id'},
                        {data: 'employee', name: 'employee'},
                        {data: 'department', name: 'department'},
                        {data: 'leave_year', name: 'leave_year'},
                        {data: 'total_days', name: 'total_days'},
                        {data: 'spent_days', name: 'spent_days'},
                        {data: 'outstanding_days', name: 'outstanding_days'},
                    ];
                loadDataAndInitializeDataTable("leave_balance", "{{ route('timesheet.leave-balances.index') }}", cols);
            });
            $(document).on('click', '#update_leave_balance', function(e){
                var url = $(this).attr('data-url');
                let leave_year = $('#filter_leave_year').val();
                let leave_type = $('#filter_leave_type').val();
                if(leave_type==='')
                {
                    alert("Select leve Type");
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: url,
                    data: ({_token:token, leave_year:leave_year, leave_type:leave_type}),
                    timeout:6000,
                    datatype: "json",
                    cache: false,
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    },
                    success: function (data) {
                        Swal.fire({
                            icon: data.Result,
                            title: 'Employee Leave Balances',
                            text: data.Message,
                        });
                        $('#leave_balance-table').DataTable().ajax.reload();
                    },
                });
            });
        })(jQuery);
    </script>
@endsection
