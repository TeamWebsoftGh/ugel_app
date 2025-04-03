
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
                        <th colspan="2">Key Activities</th>
                        <th>Status</th>
                        <th>Main Challenges</th>
                        <th>Revenue</th>
                        <th>Cost Expense</th>
                        <th>Main KPI</th>
                        <th>Time</th>
                        <th>Comments</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['tasks']  as $activities)
                        <tr>
                            <th colspan="2">
                                <span class="c_name">Responsible: {{$activities->first()->task->assignee->fullname}} </span>
                            </th>
                            <th colspan="8">
                                <span class="c_name">Task: {{$activities->first()->task->title}} </span>
                            </th>
                        </tr>
                        @forelse($activities as $task)
                            <tr>
                                <td>{{$task->task->assignee->subsidiary->name}}</td>
                                <td colspan="2">
                                    <span class="c_name">{{$task->note}} </span>
                                </td>
                                <td>{{$task->activity_status}}</td>
                                <td>{{$task->challenges}}</td>
                                <td style="text-align: right">{{format_money($task->revenue)}}</td>
                                <td style="text-align: right">{{format_money($task->expense)}}</td>
                                <td>{{$task->objective->name}}</td>
                                <td>{{$task->start_date}}</td>
                                <td>{{$task->comments}}</td>
                            </tr>
                        @empty
                        @endforelse
                    @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr style="background: #ffc107; padding: 10px">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th>TOTAL</th>
                        <th style="text-align: right">{{format_money($data['tasks']->sum('revenue'))}}</th>
                        <th style="text-align: right">{{format_money($data['tasks']->sum('expense'))}}</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">INFORMATION NOT AVAILABLE</h5>
    @endif
</div>
