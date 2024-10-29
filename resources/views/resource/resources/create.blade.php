@extends('layouts.main')
@section('title', 'Manage Resources')
@section('page-title', 'Knowledge Hub')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('resource.resources.all')}}">Knowledge Hub</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Resources</h4>
                    <p class="card-subtitle mb-4">
                    </p>
                    <div class="">
                        <table id="mini-datatable" class="table dt-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($resources as $index => $cat)
                                <tr class="item-details" data-target="#product-content" id="{{$cat->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{$cat->title}}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white" id="search_form">
                    <input type="search" id="filtercolumn_name" class="form-control" name="search_item">
                </div>
            </div> <!-- end card body-->
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Resource Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("resource.resources.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
    @include("layouts.shared.datatable")
@endsection
