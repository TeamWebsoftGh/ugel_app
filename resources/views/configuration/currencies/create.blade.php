@extends('layouts.main')
@section('title', 'Manage Currency')
@section('page-title', 'Currency')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('configuration.currencies.index')}}">Currencies</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of Currencies</h4>
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
                            @forelse($currencies as $index => $cat)
                                <tr class="item-details" data-target="#cat-content" id="{{$cat->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{$cat->currency}} ({{$cat->symbol}})</td>
                                    <td><span class="badge badge-soft-{{$cat->is_active?"success":"danger"}}">{{$cat->is_active?"Enabled":"Disabled"}}</span></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card body-->
                <div class="card-footer" id="search_form">
                    <input type="search" id="filtercolumn_name" class="form-control" name="search_item">
                </div>
            </div> <!-- end card -->
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Currency Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="cat-content">
                        @include("configuration.currencies.edit")
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
