@extends('layouts.main')
@section('title', 'Manage Categories')
@section('page-title', 'Categories')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('resource.categories.index')}}">Categories</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
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
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $index => $cat)
                                <tr class="item-details" data-target="#cat-content" id="{{$cat->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{$cat->name}}</td>
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
                    <h4 class="card-title">Category Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="cat-content">
                        @include("resource.categories.edit")
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
