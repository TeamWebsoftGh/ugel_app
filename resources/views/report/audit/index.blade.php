@extends('layouts.main')

@section('title', isset($title)?$title:"Activity Logs")
@section('page-title', 'Audit Trail')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{isset($title)?$title:"Activity Logs"}}</h4>
                    @include('report.audit.list', ['activities' => $activities])
                </div>
            </div>
        </div>
    </div>
@endsection

