@extends('layouts.admin.main')

@section('title', 'List of Customers')
@section('page-title', 'Customers')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">All Customer Accounts <span style="float: right"><a href="{{route('tasks.customers.create')}}" class="btn btn-primary">Add New</a></span></h4>
                </div>
                <div class="card-body">
                    @include('portal.customers.list', ['customers' => $customers])
                </div>
            </div>
        </div>
    </div>
@endsection

