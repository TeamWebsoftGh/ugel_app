@extends('layouts.customer.main')

@section("title", "Payment")

@section("content")
    <!--about us section start-->
    @include('layouts.website.includes.page-header')
    <!-- Main css -->
    <section class="about_section mt-60">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Pay with {{ $data['paymentMethod']->name }} <a href="javascript:void(0)" onclick="goBack()" class="btn secondary-outline-btn float-right"><i class="fa fa-arrow-left"> </i> Go Back</a></h3>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="h4">{{isset($data['order_id'])?"Balance Due ": "Wallet Top Up Amount"}} </h4>
                                        <div class="h4">{{ format_money($data['amount']) }}</div>
                                    </div>
                                    <hr>
                                    <form method="POST" enctype="multipart/form-data" action="{{route('customer.pay')}}">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="{{ $data['paymentMethod']->slug }}">
                                        <input type="hidden" name="order_id" value="{{$data['order_id']}}">
                                        <div class="form-group">
                                            <label>Amount  <span class="required">*</span></label>
                                            <div class="input-group">
                                                <button  class="btn border input-group-addon"> {{settings('currency_code')}}</button>
                                                <input type="number" min="{{$data['minPayment']}}" class="form-control" name="amount" value="{{ old('amount', $data['amount']) }}">
                                            </div>
                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{old('account_name', user('web')->email)}}" id="email" required/>
                                            <span class="input-note text-danger" id="error-email"> </span>
                                            @error('email')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="quantity" value="100">
                                            <input type="hidden" name="callback_url" value="{{route("customer.pay.status", ['order_id' => $data['order_id']])}}">
                                            <input type="hidden" name="currency" value="GHS">
                                            <input type="hidden" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" >
                                            {{-- For other necessary things you want to add to your payload. it is optional though --}}
                                            <input type="hidden" name="reference" value="{{ generate_token() }}"> {{-- required --}}

                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg btn-block confirm-button"><i
                                                class="fa fa-shopping-cart"></i> Proceed to Payment</button>
                                    </form>
                                    <br/>
                                    <br/>
                                    <div class="text-center">
                                        <h5>Powered by</h5>
                                        <img src="/img/paystack.jpeg" width="200px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="display: block">
                    @if(isset($order))
                        {{--                        @include('customer.orders.partials.summary')--}}
                    @endif
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
