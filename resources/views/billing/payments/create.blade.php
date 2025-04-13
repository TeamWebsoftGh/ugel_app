@extends('layouts.main')
@section('title', 'Manage Payments')
@section('page-title', 'Make Payment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('payments.index')}}">Payments</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Make Payment</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        <h4>Select a payment method</h4>
                        <small></small>
                        <hr/>
                        <div class="d-flex justify-content-between">
                            <h4 class="h4">Amount Due</h4>
                            <div class="h4">{{format_money(($invoice->total_amount-$invoice->total_paid))}}</div>
                        </div>
                        <p>Your Booking Number : {{$invoice->booking->booking_number}}</p>
                        @if(isset($payment_options['online']) && count($payment_options['online'])>0)
                            <p class="text-muted">Online</p>
                            <div class="list-group">
                                @foreach($payment_options['online'] as $option)
                                    <a href="{{route('payments.pay', ['slug' => $option->slug, 'invoice_id' => optional($invoice)->id, 'amount' => $invoice->total_amount])}}" class="list-group-item list-group-item-action payment_btn">
                                        Pay using {{ $option->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <br/>
                        @if(isset($payment_options['offline']) && count($payment_options['offline'])> 0)
                            <br/>
                            <p class="text-muted">Offline</p>
                            <div class="list-group">
                                @foreach($payment_options['offline'] as $option)
                                    <a href="{{route('payments.pay', ['slug' => $option->slug, 'invoice_id' => optional($invoice)->id, 'amount' => $amount])}}" class="list-group-item list-group-item-action payment_btn">
                                        {{ $option->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        @if($payment_options['show_wallet_option'])
                            <br>
                            <p class="text-muted">Wallet- Balance: {{format_money(user()->wallet()->balance())}}</p>
                            <div class="list-group">
                                <a href="{{route('payments.pay', ['slug' => "wallet", 'invoice_id' => optional($invoice)->id, 'amount' => $amount])}}" class="list-group-item list-group-item-action payment_btn">
                                    <div><i class="fas fa-wallet"></i> Pay using your wallet</div>
                                </a>
                            </div>
                        @endif
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
