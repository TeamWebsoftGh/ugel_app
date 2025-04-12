<div class="">
    <table id="datatable-buttons" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th width="15%">Title</th>
            <th width="25%">Description</th>
            <th>Subsidiary</th>
            <th>Department</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th width="15%">Title</th>
            <th width="25%">Description</th>
            <th>Subsidiary</th>
            <th>Department</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($events as $event)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$event->title}}</td>
                <td>{{\Illuminate\Support\Str::limit($event->description, 150)??"N/A"}}</td>
                <td>{{$event->department->department_name??"N/A"}}</td>
                <td>{{$event->subsidiary->name??"N/A"}}</td>
                <td>{{$event->start_date??"N/A"}}</td>
                <td>{{$event->end_date??"N/A"}}</td>
                <td class="table-action">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="ri-equalizer-fill"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item show_event" href="javascript:void(0)" data-url="{{route("events.show", $event->id)}}"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                            @if(user()->can('update-events'))
                                <li><a class="dropdown-item" href="{{route("events.edit", $event->id)}}"><i class="ri-edit-2-line me-2 align-middle text-muted"></i>Edit</a></li>
                            @endif
                            @if(user()->can('delete-events'))
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="DeleteItem('{{$event->title}}', '{{route("events.destroy", $event->id)}}')"><i class="ri-delete-bin-2-line me-2 align-middle text-muted"></i>Delete</a></li>
                            @endif
                        </ul>
                    </div>
                </td>

            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>



