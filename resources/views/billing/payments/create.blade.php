@extends('layouts.main')
@section('title', 'Manage Payments')
@section('page-title', 'Make Payment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('offers.index')}}">Payments</a></li>
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
                            <div class="h4">GHS 6,0000</div>
                        </div>
                        <p>Your offer code is : FC00012</p>
                        <p class="text-muted">Online</p>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action payment_btn">
                                Pay using Hubtel
                            </a>
                        </div>
                        <br/>
                        <p class="text-muted">Offline</p>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action payment_btn">
                                Bank Deposit
                            </a>
                            <a href="#" class="list-group-item list-group-item-action payment_btn">
                                Wire Transfer
                            </a>
                        </div>
                        <br>
                        <p class="text-muted">Wallet- Balance: GHS 0</p>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action payment_btn">
                                <div><i class="fas fa-wallet"></i> Pay using your wallet</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
