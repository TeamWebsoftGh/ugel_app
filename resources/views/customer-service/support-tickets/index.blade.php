@extends('layouts.main')

@section('title', 'My Support Tickets')
@section('page-title', 'Support Tickets')

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
                    @include('customer-service.support-tickets.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">@yield("title")</h5>
                    <div>
                        @if(user()->can(['create-'.get_permission_name()]))
                            <a class="btn btn-primary ms-auto" href="{{route("support-tickets.create")}}">Add New</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <table id="support_ticket-table" class="table">
                            <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>Ticket #</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Responsible</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/support-tickets/' ;
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                mode: "range",
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true,
                defaultDate: ["{{$data['start_date']}}", "{{$data['end_date']}}"],
                onChange: function(selectedDates, dateStr, instance) {
                    const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                    $('#filter_start_date').val(dateArr[0])
                    $('#filter_end_date').val(dateArr[1])
                },
            }); // flatpickr
        });
    </script>
    @include("layouts.shared.dt-scripts")
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                var cols =
                    [
                        {data: null, orderable: false, searchable: false},
                        {data: 'ticket_code', name: 'ticket_code'},
                        {data: 'subject', name: 'subject'},
                        {data: 'client_name', name: 'client_name'},
                        {data: 'assignee_names', name: 'assignee_names'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'status', name: 'status'},
                        {data: 'date_created', name: 'date_created'},
                        {data: 'action', name: 'action', orderable: false }
                    ];
                loadDataAndInitializeDataTable("support_ticket", "{{ route('support-tickets.index') }}", cols);
            });
        })(jQuery);
    </script>
@endsection

