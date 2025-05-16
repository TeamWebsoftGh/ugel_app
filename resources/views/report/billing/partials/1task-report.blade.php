
<div style="width:100%;display:inline;">
    @if(count($tasks) > 0)
        <div style="border: 1px solid black; height: 100%">
            <table style="width:100%;" class="task">
                <thead>
                <tr>
                    <td style="width:25%; text-align: left">
{{--                        <img src="{{ $logo }}" height="60" alt="" />--}}
                    </td>
                    <td style="text-align:center; width: 50%">
                        <h3 style=""><strong>{{strtoupper(settings("app_name"))}}</strong></h3>
                        <h6 style=""><strong>TASK REPORT</strong></h6>
                    </td>
                    <td style="width:25%; text-align: right">
{{--                        <img src="{{ $logo }}" height="60" alt="" />--}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;"><h4><strong>EMPLOYEE MONTHLY TASK REPORT</strong></h4></td>
                    <td colspan="3" style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3">
                        <table class="table-bordered">
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
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">INFORMATION NOT AVAILABLE</h5>
    @endif
</div>
