@extends('layouts.main')
@section('title', 'Manage Bulk Voice')
@section('page-title', 'Bulk Voice')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('bulk-voice.index')}}">Bulk Voice</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Bulk Voice Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="product-content">
                        @include("memo.bulk-voice.edit")
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end col -->
    </div> <!-- end col -->
@endsection
@section('js')
@endsection
