@extends('layouts.admin.main')
@section('title', 'Manage Categories')
@section('page-title', 'Categories')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('admin.configurations.categories.index')}}">Lab Types</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of Categories</h4>
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
                            @forelse($categories as $index => $usr)
                                <tr class="item-details" data-target="#user-content" id="{{$usr->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{$usr->name}}</td>
                                    <td><span class="badge badge-soft-{{$usr->is_active?"success":"danger"}}">{{$usr->is_active?"Enabled":"Disabled"}}</span></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Category Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="user-content">
                        @include("admin.configuration.categories.edit")
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
