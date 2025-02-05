@extends('layouts.main')
@section('title', 'Manage Customers')
@section('page-title', 'Customers')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                    <div>
                        @if(user()->can(['create-'.get_permission_name()]))
                            <a class="btn btn-primary ms-auto add_dt_btn" href="{{route("admin.customers.create")}}">Add New</a>
                            <a href="{{route("admin.customers.import")}}" class="btn btn-soft-info ms-auto">Import</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="client-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Client Name')}}</th>
                            <th>{{__('Client Type')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Phone Number')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Date Created')}}</th>
                            <th class="not-exported">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/clients/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'type', name: 'type'},
                        {data: 'category', name: 'category'},
                        {data: 'email', name: 'email'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("client", "{{ route('admin.customers.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
