@extends('layouts.main')
@section('page-title', 'Account')
@section('title', 'Change Password')
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Change Password</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-9 col-md-8 col-xl-6 col-xxl-4 m-t-35">
                            @include("account.password")
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
