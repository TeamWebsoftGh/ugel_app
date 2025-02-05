@extends('layouts.admin.main')
@section('title', 'Manage Customers')
@section('page-title', 'Customers')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('tasks.customers.index')}}">Customers</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of Customers</h4>
                    <p class="card-subtitle mb-4">
                    </p>
                    <div class="">
                        <table id="mini-datatable" class="table dt-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customers as $index => $usr)
                                <tr class="item-details" data-target="#user-content" data-url="{{route("tasks.customers.edit", $usr->id)}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{$usr->username}}</td>
                                    <td><span class="badge badge-{{$usr->status?"success":"danger"}}">{{$usr->status?"Enabled":"Disabled"}}</span></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="user-content">
                        @include("portal.customers.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row-->
@endsection
@section('js')
    @include("layouts.admin.shared.datatable")
@endsection
