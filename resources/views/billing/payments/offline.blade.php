@extends('layouts.main')
@section('title', 'Manage Payments')
@section('page-title', 'Make Payment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('payments.index')}}">Payments</a></li>
@endsection

@section("content")
    <!--about us section start-->
    <!-- Main css -->
    <section class="about_section mt-60">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Pay with {{ $data['paymentMethod']->name }} <a href="javascript:void(0)" onclick="goBack()" class="btn secondary-outline-btn float-right"><i class="fa fa-arrow-left"> </i> Go Back</a></h3>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="h4">{{isset($data['invoice_id'])?"Balance Due ": "Wallet Top Up Amount"}} </h4>
                                        <div class="h4">{{ format_money($data['amount']) }}</div>
                                    </div>
                                    <hr>
                                    @if($data['paymentMethod']->instruction)
                                        <div class="text-muted">
                                            <div><b>Instructions</b></div>
                                            {!! nl2br($data['paymentMethod']->instruction) !!}
                                        </div>
                                        <br>
                                    @endif
                                    <form method="POST" enctype="multipart/form-data" action="{{route('payments.store')}}">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="{{ $data['paymentMethod']->slug }}">
                                        <input type="hidden" name="invoice_id" value="{{$data['invoice_id']}}">
                                        <x-form.input-field
                                            name="amount"
                                            class="col-md-8"
                                            label="Amount"
                                            type="text"
                                            :value="$data['amount']"
                                            required
                                        />
                                        @if($data['paymentMethod']->settings->requires_transaction_number)
                                            <x-form.input-field
                                                name="reference"
                                                class="col-md-8"
                                                label="{{$data['paymentMethod']->settings->reference_field_label}}"
                                                type="text"
                                                required
                                            />
                                        @endif
                                        @if($data['paymentMethod']->settings->requires_uploading_attachment)
                                            <x-form.input-field
                                                name="attachment"
                                                label="{{$data['paymentMethod']->settings->attachment_field_label}}"
                                                type="file"
                                                required
                                                class="col-md-8"
                                            />
                                        @endif
                                        <button type="submit" class="btn btn-success btn-lg btn-block confirm-button"><i
                                                class="fa fa-shopping-cart"></i> Confirm Payment</button>
                                    </form>
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
