@extends('layouts.main')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

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
                    @include('memo.partials.filter')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="card-title">
                        All Announcements
                        @if(user()->can('create-announcements'))
                            <span style="float: right"><a href="{{route('announcements.create')}}" class="btn btn-primary">Create Announcements</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('memo.announcements.list', ['announcements' => $announcements])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/announcements';
        $(document).ready(function() {
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

            $('.show_announcement').on('click', function () {
                $('#FormModalLabel').text('{{__('Announcement Details')}}');
                let url = $(this).attr('data-url');
                ShowItem(url, '#modal_form_content');
                $('#FormModal').modal('show');
            });
        });
    </script>
    @include("layouts.shared.dt-scripts")
@endsection
