
<div style="width:100%;display:inline;">
    @if(count($data['tasks']) > 0)
        <div style="border: 1px solid black; height: 100%; padding: 5px;">
            @include("portal.shared.export-header")
            <p>Date Range: FROM {{$data['start_date']}} TO {{$data['end_date']}}</p>
            <div class="table-responsive">
                <table class="table-bordered report-datatable" style="width: 100%">
                    <thead>
                    <tr style="background: #ffc107">
                        <th>Subsidiary</th>
                        <th>Task </th>
                        <th>Key Activities</th>
                        <th>Task Status</th>
                        <th>Revenue Generated</th>
                        <th>Revenue Target</th>
                        <th>Comments</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['tasks']  as $task)
                        <tr>
                            <td>{{$task->assignee->subsidiary->name}}</td>
                            <td>{{$task->title}}</td>
                            <td>
                                {{strip_tags(\Illuminate\Support\Str::limit($task->description))}}
                            </td>
                            <td>{{$task->taskStatus->name}}</td>
                            <td style="text-align: right">{{format_money($task->timesheets->sum('revenue'))}}</td>
                            <td style="text-align: right">{{format_money($task->revenue_target)}}</td>
                            <td>{{$task->comments}}</td>
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
