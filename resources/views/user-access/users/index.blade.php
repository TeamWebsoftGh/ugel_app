@extends('layouts.main')
@section('title', 'User List')
@section('page-title', 'Users')
@section('breadcrumb')
{{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="user-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Role(s)</th>
                            <th>Last Login Date</th>
                            <th>Status</th>
                            <th>Action</th>
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
        let baseUrl = '/admin/users/' ;
        var t = $('meta[name="csrf-token"]').attr('content');

        function ResetPassword(name, url){
            bootbox.confirm("<h4>RESET PASSWORD</h4><hr /><div>Are you sure you want to reset password for <b>"+name.toUpperCase()+"</b></div>", function (result) {
                if (result === true) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: ({_token:t}),
                        timeout:60000,
                        datatype: "json",
                        cache: false,
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                            HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                        },
                        success: function (data) {
                            bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                                $('#user-table').DataTable().ajax.reload();
                            });
                        },
                    });
                }
                else {
                }
            });
        }
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'username', name: 'username',},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'role_name', name: 'role_name'},
                        {data: 'last_login_date', name: 'last_login_date'},
                        {data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("user", "{{ route('admin.users.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
