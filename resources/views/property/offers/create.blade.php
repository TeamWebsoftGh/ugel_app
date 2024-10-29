@extends('layouts.main')
@section('title', 'Manage Offers')
@section('page-title', 'Offers')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('offers.index')}}">Offers</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Offer Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("property.offers.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
