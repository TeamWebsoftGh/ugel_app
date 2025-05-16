<div class="">
    <table id="datatable-buttons" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th width="15%">Title</th>
            <th>Short Message</th>
            <th>Property type</th>
            <th>Property</th>
            <th>Client Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @php $i = 1 @endphp
        @forelse($announcements as $announcement)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$announcement->title}}</td>
                <td>{{$announcement->short_message}}</td>
                <td>{{$announcement->property_type->name??"All"}}</td>
                <td>{{$announcement->property->property_name??"All"}}</td>
                <td>{{$announcement->client_type->name??"All"}}</td>
                <td>{{$announcement->start_date??"All"}}</td>
                <td>{{$announcement->end_date??"All"}}</td>
                <td class="table-action">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="ri-equalizer-fill"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item show_announcement" href="javascript:void(0)" data-url="{{route("bulk-sms.show", $announcement->id)}}"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                            @if(user()->can('update-announcements'))
                                <li><a class="dropdown-item" href="{{route("bulk-sms.edit", $announcement->id)}}"><i class="ri-edit-2-line me-2 align-middle text-muted"></i>Edit</a></li>
                            @endif
                            @if(user()->can('delete-announcements'))
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="DeleteItem('{{$announcement->title}}', '{{route("bulk-sms.destroy", $announcement->id)}}')"><i class="ri-delete-bin-2-line me-2 align-middle text-muted"></i>Delete</a></li>
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



