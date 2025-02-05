@extends('layouts.main')

@section('title', 'My Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Filter
                    </h4>
                </div>
                <div class="card-body">
                    @include('customer-service.support-tickets.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Support Tickets
                        <span style="float: right"><a href="{{route('support-tickets.create')}}" class="btn btn-primary">Add New</a></span>
                    </h4>
                </div>
                <div class="card-body">
                    @include('customer-service.support-tickets.list')
                </div>
            </div>
        </div>
    </div>
@endsection


