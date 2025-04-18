@extends('layouts.main')

@section('title', 'All Notifications')
@section('page-title', 'Notifications')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title"> All Notifications {{isset($user)?' for '.$user->username: ''}} </h4>
                </div>
                <div class="card-body">
                    @include('notifications.list', ['notifications' => $notifications])
                </div>
            </div>
        </div>
    </div>
@endsection

