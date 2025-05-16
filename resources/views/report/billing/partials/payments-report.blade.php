
<div style="width:100%;display:inline;">
    @if(count($data['payments']) > 0)
        <div style="border: 1px solid black; height: 100%; padding: 5px;">
            @include("shared.export-header")
            <p style="text-align: center">Date Range: FROM {{$data['start_date']}} TO {{$data['end_date']}}</p>
            <div class="table-responsive">
                <table class="table-bordered report-datatable" style="width: 100%">
                    <thead>
                    <tr style="background: #153e6f; color: #ffffff!important; padding: 5px">
                        <th style="color: #ffffff">#</th style="color: #ffffff">
                        <th style="color: #ffffff">Invoice #</th>
                        <th style="color: #ffffff">Transaction #</th>
                        <th style="color: #ffffff">Payment Gateway</th>
                        <th style="color: #ffffff">Customer Name</th>
                        <th style="color: #ffffff">Student Number/Username</th>
                        <th style="color: #ffffff">Amount</th>
                        <th style="color: #ffffff">Status</th>
                        <th style="color: #ffffff">Payment Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1 @endphp
                    @forelse($data['payments']  as $task)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$task?->invoice?->invoice_number}}</td>
                            <td>{{$task?->transaction_id}}</td>
                            <td>{{$task?->paymentGateway->name}}</td>
                            <td>{{$task?->client?->fullname}}</td>
                            <td>{{$task->client->client_number??$task->client->username}}</td>
                            <td>{{$task->amount}}</td>
                            <td>{{$task->status}}</td>
                            <td>{{$task->payment_date}}</td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h5 style="text-align:center;color:darkred">NO RECORD AVAILABLE</h5>
    @endif
</div>
