<div class="table-responsive">
    <table id="datatable-buttons" class="table dt-responsive">
        <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Total Orders</th>
            <th>Last Active</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Total Orders</th>
            <th>Last Active</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($customers as $customer)
            <tr>
                <td>{{$i++}}</td>
                <td>
                    <span class="c_name">{{$customer->username}} </span>
                </td>
                <td>{{$customer->email}}</td>
                <td>{{$customer->phone_number??"N/A"}}</td>
                <td>{{$customer->orders->count()}}</td>
                <td>{{$customer->created_at}}</td>
                <td><span class="badge badge-{{$customer->status?'success':'danger'}}">{{$customer->status?'Enabled':'Disabled'}}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <a class="dropdown-item" href="{{route('tasks.customers.edit', $customer->id)}}"><i class="fa fa-eye"></i> Edit</a>
                            <a class="dropdown-item text-danger" onclick="DeleteItem('{{$customer->fullname}}', '{{route('tasks.customers.destroy', $customer->id)}}')" href="#"><i class="fa fa-trash"></i> Delete</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" onclick="ChangeStatus('{{$customer->fullname}}', '{{$customer->status}}','{{route('tasks.customers.change-status', $customer->id)}}')" href="#"><i class="fa fa-{{$customer->status?'ban':'check'}}"></i> {{$customer->status?'Deactivate':'Activate'}}</a>
                            <a class="dropdown-item" onclick="ResetPassword('{{$customer->fullname}}', '{{route('tasks.customers.reset-password', $customer->id)}}')" href="#"><i class="fa fa-file-signature"></i> Reset Password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('tasks.customers.edit', $customer->id)}}"><i class="fa fa-eye"></i> Orders</a>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>
@section('js')
    <script>
        let baseUrl = '/tasks/writers/';
    </script>
    @include("layouts.admin.shared.datatable")
@endsection


