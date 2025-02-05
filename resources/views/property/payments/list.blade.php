<div class="">
    <table id="datatable-buttons" class="table dt-responsive">
        <thead>
        <tr>
            <th>#</th>
            <th>Transaction #</th>
            <th>Payment Gateway </th>
            <th>Customer</th>
            <th>Payment Reason</th>
            <th>Amount</th>
            <th>Attachment</th>
            <th>Date Created</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Transaction #</th>
            <th>Payment Gateway </th>
            <th>Customer</th>
            <th>Payment Reason</th>
            <th>Amount</th>
            <th>Attachment </th>
            <th>Date Created</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
{{--        @forelse($payments as $index => $payment)--}}
{{--            <tr>--}}
{{--                <td>{{$index+1}}</td>--}}
{{--                <td>{{$payment->transaction_id}}</td>--}}
{{--                <td>--}}
{{--                    <span class="c_name">{{$payment->paymentGateway->name}} </span>--}}
{{--                </td>--}}
{{--                <td>{{$payment->paymentable->username}}</td>--}}
{{--                <td>{{$payment->order_id?"Order":"Wallet Top-up"}}</td>--}}
{{--                <td>{{currency()}} {{$payment->amount}}</td>--}}
{{--                <td>--}}
{{--                    @if($payment->attachment)--}}
{{--                        <a href="{{asset("storage/$payment->attachment")}}">View Attachment</a>--}}
{{--                    @else--}}
{{--                        N/A--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--                <td>{{format_date($payment->created_at)}}</td>--}}
{{--                <td>{{$payment->status}}</td>--}}
{{--                <td>--}}
{{--                    <div class="btn-group" role="group">--}}
{{--                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>--}}
{{--                        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">--}}
{{--                            <a class="dropdown-item" href="{{route('admin.payments.show', $payment->transaction_id)}}"><i class="fa fa-eye"></i> View</a>--}}
{{--                            @if($payment->status == "success")--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                                <a class="dropdown-item text-warning" onclick="ChangePaymentStatus('{{$payment->paymentGateway->name}}', '2','{{route('admin.payments.change-status', $payment->id)}}')" href="#"><i class="fa fa-recycle"></i> Reverse</a>--}}
{{--                            @elseif($payment->status == "pending")--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                                <a class="dropdown-item  text-success" onclick="ChangePaymentStatus('{{$payment->paymentGateway->name}}', '1', '{{route('admin.payments.change-status', $payment->id)}}')" href="#"><i class="fa fa-check-circle"></i> Confirm Payment</a>--}}
{{--                                <a class="dropdown-item text-danger" onclick="ChangePaymentStatus('{{$payment->paymentGateway->name}}', '0', '{{route('admin.payments.change-status', $payment->id)}}')" href="#"><i class="fa fa-times-circle"></i> Reject Payment</a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @empty--}}
{{--        @endforelse--}}
        </tbody>
    </table>
</div>


