
<div style="width:100%;display:inline;">
    @if(count($data['loan_repayments']) > 0)
        <div style="border: 1px solid black; height: 100%; padding: 5px;">
            @include("shared.export-header")
            <p>Date Range: FROM {{$data['start_date']}} TO {{$data['end_date']}}</p>
            <div class="table-responsive">
                <table class="table-bordered report-datatable" style="width: 100%">
                    <thead>
                    <tr style="background: #a52c3f; color: #fff; padding: 5px">
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Client Phone</th>
                        <th>Loan Product</th>
                        <th>Loan Reference</th>
                        <th>Repayment Date</th>
                        <th>Installment</th>
                        <th>Total Due</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['loan_repayments']  as $task)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$task->loan?->client?->fullname}}</td>
                            <td>{{$task->loan?->client?->phone_number}}</td>
                            <td>{{$task->loan?->loan_product?->name}}</td>
                            <td>{{$task->loan?->reference}}</td>
                            <td>{{$task->due_date}}</td>
                            <td>{{$task->installment}}</td>
                            <td>{{$task->total_due}}</td>
                            <td>{{$task->status}}</td>
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
