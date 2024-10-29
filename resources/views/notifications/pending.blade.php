@extends('layouts.admin.main')

@section('title', 'List of Writers Pending')
@section('page-title', 'Writers')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">All Pending Accounts <span style="float: right"><a href="{{route('tasks.writers.create')}}" class="btn btn-primary">Add New</a></span></h4>
                </div>
                <div class="card-body">
                    @include('portal.writers.list', ['writers' => $writers])
                </div>
            </div>
        </div>
    </div>
@endsection

