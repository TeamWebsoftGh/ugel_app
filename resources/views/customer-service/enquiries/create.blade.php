@extends('layouts.main')
@section('title', 'Manage Enquiries')
@section('page-title', 'Enquiries')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('enquiries.index')}}">Categories</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-8 col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Category Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="cat-content">
                        @include("customer-service.enquiries.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row-->
@endsection
@section('js')
    @include("layouts.shared.datatable")
@endsection
