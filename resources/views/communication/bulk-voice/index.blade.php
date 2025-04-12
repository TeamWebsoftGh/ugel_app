@extends('layouts.main')

@section('title', 'Bulk VOICE')
@section('page-title', 'Bulk VOICE')

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
                        All Bulk Sms
                        @if(user()->can('create-bulk-voice'))
                            <span style="float: right"><a href="{{route('bulk-voice.create')}}" class="btn btn-primary">Create Bulk Voice</a></span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @include('communication.bulk-voice.list', ['announcements' => $announcements])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/bulk-bulk-voice';
        let ctoken = $('meta[name="csrf-token"]').attr('content');
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
                $('#FormModalLabel').text('{{__('Bulk Voice Details')}}');
                let url = $(this).attr('data-url');
                ShowItem(url, '#modal_form_content');
                $('#FormModal').modal('show');
            });
        });

        function DeleteItem(name, url) {
            bootbox.confirm("<h4>DELETE</h4><hr /><div>This is an irrevisable action that will delete <b>" + name.toUpperCase() + "</b>. Are you sure you want to <b><span style='color:red'>delete </span>" + name.toUpperCase() + "</b></div>", function (result) {
                if (result === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: ({_token: ctoken}),
                        timeout: 60000,
                        datatype: "json",
                        cache: false,
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                        },
                        success: function (data) {
                            bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                                window.location.reload();
                            });
                        },
                    });
                } else {
                }
            });
        }

    </script>
    @include("layouts.shared.dt-scripts")

@endsection
