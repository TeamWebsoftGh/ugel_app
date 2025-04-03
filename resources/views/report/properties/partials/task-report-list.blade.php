
<div style="width:100%;display:inline;">
    @if(count($tasks) > 0)
        <div class="table-responsive" style="border: 1px solid black; height: 100%">
            <h3 style="text-align: center; padding: 15px;">EMPLOYEE MONTHLY TASK REPORT</h3>
            <h4 style="text-align: center; padding: 15px;">FROM {{$data['start_date']}} TO {{$data['end_date']}}</h4>
            <table class="table-bordered" style="width: 100%">
                <thead>
                <tr style="background: #ffc107">
                    <th>Task #</th>
                    <th>Subsidiary</th>
                    <th>Name/Description</th>
                    <th>Key Objectives</th>
                    <th>Anticipated Challenges/Opportunities</th>
                    <th>Remarks</th>
                    <th>Responsible</th>
                    <th>Deadline</th>
                    <th>Month</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @php $i = 1 @endphp
                @forelse($tasks as $task)
                    <tr>
                        <td>{{$task->code}}</td>
                        <td>{{$task->createdBy->subsidiary->name}}</td>
                        <td>
                            <span class="c_name">{{$task->title}} </span>
                        </td>
                        <td>{{strip_tags(\Illuminate\Support\Str::limit($task->description))}}</td>
                        <td>{{$task->challenges}}</td>
                        <td>{{$task->remarks}}</td>
                        <td>{{$task->assignee->fullname}}</td>
                        <td>{{$task->due_date}}</td>
                        <td>{{format_date($task->start_date, 'M')}}</td>
                        <td>{{$task->taskStatus->name}}</td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">INFORMATION NOT AVAILABLE</h5>
    @endif
</div>
