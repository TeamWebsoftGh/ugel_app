
<div style="width:100%;display:inline;">
    @if(count($data['tasks']) > 0)
        <div style="border: 1px solid black; height: 100%; padding: 5px;">
            @include("portal.shared.export-header")
            <p>Date Range: FROM {{$data['start_date']}} TO {{$data['end_date']}}</p>
            <div class="table-responsive">
                <table class="table-bordered report-datatable" style="width: 100%">
                    <thead>
                    <tr style="background: #ffc107">
                        <th>Task #</th>
                        <th>Subsidiary</th>
                        <th>Name/Description</th>
                        <th>Key Objectives</th>
                        <th>Employee Score</th>
                        <th>Remarks</th>
                        <th>Responsible</th>
                        <th>Month</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['tasks']  as $task)
                        <tr>
                            <td>{{$task->code}}</td>
                            <td>{{$task->createdBy->subsidiary->name}}</td>
                            <td>
                                <span class="c_name">{{$task->title}} </span>
                            </td>
                            <td>{{strip_tags(\Illuminate\Support\Str::limit($task->description))}}</td>
                            <td>{{$task->employee_score}}/{{$task->total_weightage}}</td>
                            <td>{{$task->remarks}}</td>
                            <td>{{$task->assignee->fullname}}</td>
                            <td>{{format_date($task->start_date, 'F')}}</td>
                            <td>{{$task->taskStatus->name}}</td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">INFORMATION NOT AVAILABLE</h5>
    @endif
</div>
