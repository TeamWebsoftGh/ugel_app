@extends('layouts.main')

@section('title', 'Events')
@section('page-title', 'Events')

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
                    @include('communication.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        All Events
                        @if(user()->can('create-events'))
                            <span style="float: right"><a href="{{route('events.create')}}" class="btn btn-primary">Add New</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('communication.events.list', ['events' => $events])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/events';
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                mode: "range",
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true,
                defaultDate: ["{{$data['start_date']}}", "{{$data['end_date']}}"],
                onChange: function (selectedDates, dateStr, instance) {
                    const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                    $('#filter_start_date').val(dateArr[0])
                    $('#filter_end_date').val(dateArr[1])
                },
            }); // flatpickr

            $('.show_announcement').on('click', function () {
                $('#FormModalLabel').text('{{__('Event Details')}}');
                let url = $(this).attr('data-url');
                ShowItem(url, '#modal_form_content');
                $('#FormModal').modal('show');
            });
        });
    </script>
    @include("layouts.shared.dt-scripts")
@endsection
