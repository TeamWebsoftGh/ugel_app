@extends('layouts.main')

@section('title', 'Offers')
@section('page-title', 'Offers')

@section('content')
    @include('property.partials.filter')
    <div class="row">
        <div class="col-xxl-3 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body bg-success-subtle">
                    <h5 class="fs-17 text-center mb-0">Active Offers</h5>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->

        <div class="col-xxl-3 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body bg-danger-subtle">
                    <h5 class="fs-17 text-center mb-0">Cancelled/Declined</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->

        <div class="col-xxl-3 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body bg-primary-subtle">
                    <h5 class="fs-17 text-center mb-0">Queued Offers</h5>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>

        </div>
        <!--end col-->

        <div class="col-xxl-3 col-md-6">
            <div class="card overflow-hidden">
                <div class="card-body bg-info-subtle">
                    <h5 class="fs-17 text-center mb-0">New Offers</h5>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <div class="avatar-title bg-light rounded material-shadow">
                                {{--                                <img src="/assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs">--}}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fs-15 mb-1">Websoft</h5>
                            <p class="text-muted mb-2">MTN Services</p>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary">View Details <i
                                        class="ri-arrow-right-up-line align-bottom"></i></a>
                        </div>
                    </div>
                    <h6 class="text-muted mb-0">GHS15,00,000 <span class="badge bg-success-subtle text-success"></span>
                    </h6>
                </div>
                <div class="card-body border-top border-top-dashed">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                        <h6 class="flex-shrink-0 text-danger mb-0"><i class="ri-time-line align-bottom"></i> 05 Days
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/offers';
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
                $('#FormModalLabel').text('{{__('Announcement Details')}}');
                let url = $(this).attr('data-url');
                ShowItem(url, '#modal_form_content');
                $('#FormModal').modal('show');
            });
        });
    </script>
    @include("layouts.shared.dt-scripts")
@endsection
