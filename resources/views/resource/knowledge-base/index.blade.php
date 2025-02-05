@extends('layouts.main')

@section('title', 'Knowledge Base')
@section('page-title', 'Knowledge Base')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Topics
                        @if(user()->can('create-knowledge-bases'))
                            <span style="float: right"><a href="{{route('resource.knowledge-base.index')}}" class="btn btn-primary">Manage Topics</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('resource.knowledge-base.list', ['topics' => $topics])
                </div>
            </div>
        </div>
    </div>
@endsection

