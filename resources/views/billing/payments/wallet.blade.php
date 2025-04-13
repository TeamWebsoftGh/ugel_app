@extends('layouts.customer.main')

@section("title", "Payment")

@section("content")
    @include('layouts.website.includes.page-header')
    <section class="about_section mt-60">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Pay with your Wallet <a href="{{route('customer.orders.edit', $order->id)}}" class="btn secondary-outline-btn float-right"><i class="fa fa-arrow-left"> </i> Go to Edit Order</a></h3>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="h4">Balance Due</h4>
                                        <div class="h4">{{ format_money($order->balance) }}</div>
                                    </div>
                                    <hr>
                                    <form method="POST" enctype="multipart/form-data" action="{{route('customer.pay')}}">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="wallet">
                                        <input type="hidden" name="order_id" value="{{$order->id}}">
                                        <input type="hidden" name="orderID" value="{{$order->order_no}}">
                                        <div class="form-group">
                                            <label>Wallet Balance</label>
                                            <input type="text"  class="form-control" readonly value="{{format_money(user()->wallet()->balance())}}">
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Amount  <span class="required">*</span></label>
                                            <div class="input-group">
                                                <input type="number" min="{{$order->minPayment}}" class="form-control" readonly name="amount" value="{{ old('amount', $order->balance) }}">
                                            </div>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg btn-block confirm-button"><i
                                                class="fa fa-shopping-cart"></i> Proceed to Payment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="display: block">
{{--                    @include('customer.orders.partials.summary')--}}
                </div>
            </div>
        </div>
    </section>
    <!--about us section end-->
    <!--header section end-->
@endsection

@section("js")
    <!-- JS -->

@endsection
