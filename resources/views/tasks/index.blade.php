@extends('layouts.main')

@section('title', 'My Tasks')
@section('page-title', 'Tasks')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        Filter
                    </h4>
                </div>
                <div class="card-body">
                    @include('tasks.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        TASKS
                        @if(user()->can('create-tasks') && !isset($user))
                            <span style="float: right"><a href="{{route('tasks.create')}}" class="btn btn-primary">Create Task</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('tasks.list')
                </div>
            </div>
        </div>
    </div>
@endsection


