<div class="">
    <table id="datatable-buttons" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th width="15%">Title</th>
            <th width="25%">Description</th>
            <th>Company</th>
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
            <th>Company</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @php $i = 1 @endphp
        @forelse($announcements as $announcement)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$announcement->title}}</td>
                <td>{{\Illuminate\Support\Str::limit($announcement->description, 150)??"N/A"}}</td>
                <td>{{$announcement->company->company_name??"All"}}</td>
                <td>{{$announcement->start_date??"All"}}</td>
                <td>{{$announcement->end_date??"All"}}</td>
                <td class="table-action">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="ri-equalizer-fill"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item show_announcement" href="javascript:void(0)" data-url="{{route("announcements.show", $announcement->id)}}"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                            @if(user()->can('update-announcements'))
                                <li><a class="dropdown-item" href="{{route("announcements.edit", $announcement->id)}}"><i class="ri-edit-2-line me-2 align-middle text-muted"></i>Edit</a></li>
                            @endif
                            @if(user()->can('delete-announcements'))
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="DeleteItem('{{$announcement->title}}', '{{route("announcements.destroy", $announcement->id)}}')"><i class="ri-delete-bin-2-line me-2 align-middle text-muted"></i>Delete</a></li>
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



