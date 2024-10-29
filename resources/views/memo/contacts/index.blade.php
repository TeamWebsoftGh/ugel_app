@extends('layouts.main')
@section('title', 'Manage Contacts')
@section('page-title', 'Contacts')
@section('breadcrumb')
    {{--    <li class="breadcrumb-item"><a href="{{route('awards.index')}}">Employee awards</a></li>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="contact-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Phone Number')}}</th>
                            <th>{{__('Email Address')}}</th>
                            <th>{{__('Company')}}</th>
                            <th>{{__('Contact Group')}}</th>
                            <th>{{__('Status')}}</th>
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
        let baseUrl = '/contacts/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'fullname', name: 'fullname'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'email', name: 'email'},
                        {data: 'company', name: 'company'},
                        {data: 'contact_group_name', name: 'contact_group_name'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("contact", "{{ route('contacts.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
