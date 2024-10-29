<div class="table-responsive">
    <table id="buttons-datatables" class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            @if(isset($title) && $title == "Error Logs")
                <th>Error Code</th>
                <th>Error Line</th>
                <th style="width: 25%; word-break: break-all">Error File</th>
            @endif
            <th>User</th>
            <th style="width: 25%; word-break: break-all">Description</th>
        @if(user()->hasRole('developer'))<th>IP Address</th>@endif
            <th>Created At</th>
        </tr>
        </thead>
        <tbody>
        @php $i = 1@endphp
        @foreach ($activities as $activity)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{!! optional($activity->logAction)->name !!}</td>
                @if(isset($title) && $title == "Error Logs")
                    <td>{!! $activity->code !!}</td>
                    <td>{!! $activity->line !!}</td>
                    <td style="width: 25%; word-break: break-all" class="text-wrap">{!! $activity->file !!}</td>
                @endif
                <td>{{ isset($activity->user)? $activity->user->fullname:'System' }}</td>
                <td>{!! $activity->description??$activity->message !!}</td>
                @if(user()->hasRole('developer'))<td>{{$activity->client_ip}}</td>@endif
                <td>{{ $activity->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@section('js')
    @include("layouts.shared.datatable")
@endsection


