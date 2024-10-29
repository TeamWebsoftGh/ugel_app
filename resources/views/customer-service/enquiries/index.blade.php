@extends('layouts.main')
@section('title', 'Manage Enquiries')
@section('page-title', 'Enquiries')

@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                @include("layouts.partials.dt-header")
                <div class="card-body">
                    <p class="card-subtitle mb-4"></p>
                    <table id="enquiry-table" class="table dt-responsive" style="box-shadow: none">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th>Action</th>
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
        let baseUrl = '/customer-service/enquiries/' ;
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'full_name', name: 'full_name'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'email', name: 'email'},
                        {data: 'subject', name: 'subject'},
                        {data: 'message', name: 'message'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'is_active', name: 'is_active'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("enquiry", "{{ route('enquiries.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection
