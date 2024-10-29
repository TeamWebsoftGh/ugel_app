<div class="">
    <table id="datatable-buttons" class="table dt-responsive">
        <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>User Role</th>
            <th>Last Active</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @php $i = 1 @endphp
        @forelse($users as $user)
            <tr>
                <td>{{$i++}}</td>
                <td>
                    <span class="c_name">{{$user->fullname}} @if($user->role == "tasks") <span class="badge badge-default m-l-10 hidden-sm-down">Admin</span> @endif</span>
                </td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone_number??"N/A"}}</td>
                <td>{{$user->RoleName}}</td>
                <td>{{$user->created_at}}</td>
                <td><span class="badge badge-{{$user->status?'success':'danger'}}">{{$user->status?'Enabled':'Disabled'}}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <a class="dropdown-item" href="{{route('tasks.users.edit', $user->id)}}"><i class="fa fa-eye"></i> Edit</a>
                            <a class="dropdown-item text-danger" onclick="DeleteItem('{{$user->fullname}}', '{{route('tasks.users.destroy', $user->id)}}')" href="#"><i class="fa fa-trash"></i> Delete</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" onclick="ChangeStatus('{{$user->fullname}}', '{{$user->status}}','{{route('tasks.users.change-status', $user->id)}}')" href="#"><i class="fa fa-{{$user->status?'ban':'caret-square-o-right'}}"></i> {{$user->status?'Deactivate':'Activate'}}</a>
                            <a class="dropdown-item" onclick="ResetPassword('{{$user->fullname}}', '{{route('tasks.users.reset-password', $user->id)}}')" href="#"><i class="fa fa-chain"></i> Reset Password</a>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>
<script>
    $("#datatable-buttons").DataTable( {
            lengthChange:!1, buttons:["copy", "print", "pdf"], language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            scrollY: 200
            , drawCallback:function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        }
    );
</script>


