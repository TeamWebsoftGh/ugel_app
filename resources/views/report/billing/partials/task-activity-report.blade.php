
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
                        <th>Name</th>
                        <th>Key Objectives</th>
                        <th>Revenue Generated</th>
                        <th>Revenue Target</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['tasks']  as $task)
                        <tr>
                            <td>{{$task->assignee->subsidiary->name}}</td>
                            <td>
                                <span class="c_name">{{$task->title}} </span>
                            </td>
                            <td>
                                <ul>
                                    @forelse($task->timesheets as $t)
                                        <li>{{$t->note}}</li>
                                    @empty
                                    @endforelse
                                </ul>
                            </td>
                            <td style="text-align: right">{{format_money($task->timesheets->sum('revenue'))}}</td>
                            <td style="text-align: right">{{format_money($task->revenue_target)}}</td>
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
