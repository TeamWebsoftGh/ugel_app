@extends('layouts.portal.main')
@section('title', 'Manage Permissions')
@section('page-title', 'User Access')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('tasks.permissions.index')}}">Permissions</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of Permissions</h4>
                    <p class="card-subtitle mb-4">
                    </p>
                    <div class="">
                        <table id="mini-datatable" class="table dt-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card body-->
                <div class="card-footer bg-white">
                    <input type="search" class="form-control" id="filtercolumn_name" name="name">
                </div>
            </div> <!-- end card -->
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Permission Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="cat-content">
                        @include("portal.user-access.permissions.edit")
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row-->
@endsection
@section('js')
    @include("layouts.portal.shared.datatable")
@endsection
