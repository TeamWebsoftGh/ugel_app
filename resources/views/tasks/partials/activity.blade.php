<h5 class="card-title mb-4">
    Activities
    @if(user()->id == $task->assignee_id && !$task->is_closed)
        <button class="btn btn-info add-btn float-end" id="activityModalBtn"><i
                class="ri-add-fill me-1 align-bottom"></i> Add Activity</button>@endif</h5>
<div data-simplebar style="height: 380px;" class="px-3 mx-n3 mb-2">
    <div class="table-responsive">
        <table class="table align-middle mb-0 task-datatable">
            <thead class="table-light text-muted">
            <tr>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Revenue</th>
                <th scope="col">Expense</th>
                <th scope="col">Note</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activities as $act)
                <tr>
                    <td>{{$act->start_date}}</td>
                    <td>{{$act->end_date}}</td>
                    <td>{{format_money($act->revenue)}}</td>
                    <td>{{format_money($act->expense)}}</td>
                    <td style="width: 30%">{{\Illuminate\Support\Str::limit($act->note, 200)}}</td>
                    <td>{{$act->status}}</td>
                    <td class="table-action">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="true">
                                <i class="ri-equalizer-fill"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                <li><a class="dropdown-item"  onclick="EditActivity('{{$task->id}}', '{{$act->id}}')"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                @if(user()->id == $task->assignee_id && !$task->is_closed)
                                    <li><a class="dropdown-item" onclick="DeleteItem('Activity ', '{{route("tasks.activities.destroy", ['task_id' => $task->id, 'id'=> $act->id])}}')" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table><!--end table-->
    </div>
</div>
<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-soft-info p-3">
                <h5 class="modal-title" id="exampleModalLabel">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <div id="activity_container1">
                @include("portal.tasks.partials.edit-activity")
            </div>
        </div>
    </div>
</div>
