@extends('layouts.main')
@section('title', 'Payments')
@section('page-title', 'Payments')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title"> All Payments </h4>
                </div>
                <div class="card-body">
                    @include('property.payments.list')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let baseUrl = '/property/travels/';
    </script>
    @include("layouts.shared.dt-scripts")

@endsection
