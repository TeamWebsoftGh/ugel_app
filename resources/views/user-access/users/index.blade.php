@extends('layouts.admin.main')

@section('title', 'List of User Accounts')
@section('page-title', 'User Accounts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">All User Accounts <span style="float: right"><a href="{{route('portal.users.create')}}" class="btn btn-primary">Add New</a></span></h4>
                </div>
                <div class="card-body">
                    @include('portal.user-access.users.list', ['users' => $users])
                </div>
            </div>
        </div>
    </div>
@endsection

