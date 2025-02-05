@extends('layouts.main')

@section('title', 'Resources')
@section('page-title', 'Resources')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Resources
                        @if(user()->can('create-knowledge-bases'))
                            <span style="float: right"><a href="{{route('resource.resources.index')}}" class="btn btn-primary">Manage Resources</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('resource.resources.list', ['resources' => $resources])
                </div>
            </div>
        </div>
    </div>
@endsection

