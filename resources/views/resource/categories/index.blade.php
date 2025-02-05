@extends('layouts.admin.main')

@section('title', 'List of Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">All Categories <span style="float: right"><a href="{{route('categories.create')}}" class="btn btn-primary">Add New</a></span></h4>
                </div>
                <div class="card-body">
                    @include('portal.categories.list', ['categories' => $categories])
                </div>
            </div>
        </div>
    </div>
@endsection

