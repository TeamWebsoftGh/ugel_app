<div class="">
    <table id="datatable-buttons" class="table">
        <thead>
        <tr role="row">
            <th>#</th>
            <th>Task#</th>
            <th>Title</th>
            <th>Status</th>
            <th>Date</th>
            @if(!isset($user))
            <th>Responsible</th>
            @endif
            <th>Weightage</th>
            <th>Created By</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tasks as $index => $task)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{$task->code}}</td>
                <td>{{$task->title}}</td>
                <td>{{$task->taskStatus->name}}</td>
                <td>Start date: {{$task->start_date}}<br/>Due Date: {{$task->due_date}}</td>
                @if(!isset($user))
                <td>{{$task->assignee->fullname}}<br/>{{$task->assignee->subsidiary->name}}</td>
                @endif
                <td>Total Weight: {{$task->total_weightage}}<br/>Employee Score: {{$task->employee_score??"N/A"}}</td>
                <td>{{$task->createdBy->fullname}}</td>
                <td class="table-action">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="ri-equalizer-fill"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item" href="{{route("tasks.show", $task->id)}}"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                            @if($task->assignee_id != user()->id && !$task->is_closed)
                                <li><a class="dropdown-item" href="{{route("tasks.edit", $task->id)}}"><i class="ri-edit-2-line me-2 align-middle text-muted"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
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
@section('js')
    <script>
        let baseUrl = '/tasks/knowledge-base/';
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                mode: "range",
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true,
                defaultDate: ["{{$data['start_date']}}", "{{$data['end_date']}}"],
                onChange: function(selectedDates, dateStr, instance) {
                    const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                    $('#filter_start_date').val(dateArr[0])
                    $('#filter_end_date').val(dateArr[1])
                },
            }); // flatpickr
        });
    </script>
    @include("layouts.shared.dt-scripts")
@endsection


