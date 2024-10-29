@extends('layouts.main')
@section('title', 'Manage Service Types')
@section('page-title', 'Service Types')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('service-types.index')}}">Service Types</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Service Type Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("service-types.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
