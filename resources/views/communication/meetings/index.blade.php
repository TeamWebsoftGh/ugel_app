@extends('layouts.main')

@section('title', 'Meetings')
@section('page-title', 'Meetings')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        All Meetings
                        @if(user()->can('create-meetings'))
                            <span style="float: right"><a href="{{route('meetings.create')}}" class="btn btn-primary">Add New</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('communication.meetings.list', ['meetings' => $meetings])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('.show_announcement').on('click', function () {
                $('#FormModalLabel').text('{{__('Meeting Details')}}');
                let url = $(this).attr('data-url');
                ShowItem(url, '#modal_form_content');
                $('#FormModal').modal('show');
            });
        });
    </script>
    @include("layouts.shared.datatable")
@endsection
