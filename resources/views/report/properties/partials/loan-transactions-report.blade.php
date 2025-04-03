
<div style="width:100%;display:inline;">
    @if(count($data['loan_transactions']) > 0)
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
                        <th>Reference</th>
                        <th>Trans Type</th>
                        <th>Repayment Date</th>
                        <th>Transaction Date</th>
                        <th>Total Paid</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['loan_transactions']  as $task)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$task->loan?->client?->fullname}}</td>
                            <td>{{$task->loan?->client?->phone_number}}</td>
                            <td>{{$task->loan?->loan_product?->name}}</td>
                            <td>{{$task->reference}}</td>
                            <td>{{$task->loan_transaction_type->name}}</td>
                            <td>{{$task->due_date}}</td>
                            <td>{{$task->submitted_on}}</td>
                            <td>{{$task->amount}}</td>
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
