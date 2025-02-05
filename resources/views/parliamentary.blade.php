@extends('layouts.main')
@section('title', "Dashboard")
@section('page-title', trans('file.Welcome') .' '.user()->name)
@section('breadcrumb')
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Welcome {{user()->fullname}}!</h4>
                                {{--                                <p class="text-muted mb-0">Here's what's happening with your task today.</p>--}}
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <div class="row g-3 mb-0 align-items-center">
                                    <!--end col-->
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-soft-success"><i
                                                class="ri-task-line align-middle me-1"></i>
                                            Valid Votes</a>
                                    </div>
                                    <!--end col-->
                                    <div class="col-auto">
                                        <button type="button"
                                                class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i
                                                class="ri-pulse-line"></i></button>
                                    </div>
                                    <!--end col-->

                                </div>
                                <!--end row-->
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
                <div class="row">
                    <div class="col-xxl-4 col-lg-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Total Votes</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" style="">

                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-body pt-0">
                                <div data-simplebar style="max-height: 430px;">
                                    <ul class="list-group list-group-flush border-dashed">
                                        @forelse($items as $it)
                                            @forelse($it as $item)
                                                <div
                                                    class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                                                    <p class="fw-medium mb-0">
                                                        <img src="{{ $item->image_url }}" alt="" class="avatar-xs rounded-circle acitivity-avatar">

                                                        {{$item->candidate_name}}</p>
                                                    <div>
                                                        <span class="text-muted pe-5">{{($item->total_votes)}} Vote(s)</span>
                                                    </div>
                                                </div><!-- end -->
                                            @empty
                                            @endforelse
                                        @empty
                                        @endforelse
                                    </ul><!-- end -->
                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-8 order-xxl-0 order-first">
                        <div class="d-flex flex-column h-100">
                            <div class="row h-100">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                            <i class="ri-user-2-line align-middle"></i>
                                                        </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <a href="#" class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                        Votes</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$voteSummary?->first()['polling_stations_with_votes']}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                            <i class="ri-arrow-up-circle-fill align-middle"></i>
                                                        </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Polling Stations</p>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$ps_count}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                            <i class="ri-task-line align-middle"></i>
                                                        </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <a href="#" class="text-uppercase fw-semibold fs-12 text-muted mb-1">Electoral Areas</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$ea_count}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div><!-- end row -->

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h4 class="card-title flex-grow-1 mb-0">Total Votes</h4>
                                        </div><!-- end cardheader -->
                                        <div class="card-body">
                                            <div class="table-responsive table-card">
                                                <table class="table dashboard-table table-centered align-middle">
                                                    <thead class="bg-light text-muted">
                                                    <tr>
                                                        <th scope="col">Position</th>
                                                        <th scope="col">Candidate</th>
                                                        <th scope="col">Political Party</th>
                                                        <th scope="col">Total Votes</th>
                                                        <th scope="col">Percentage</th>
                                                    </tr><!-- end tr -->
                                                    </thead><!-- thead -->
                                                    <tbody>
                                                    @forelse($items as $it)
                                                        @forelse($it  as $index  => $item)
                                                            <tr>
                                                                <td>{{++$index}}</td>
                                                                <td>
                                                                    <img src="{{asset($item->image_url)}}" class="avatar-xs rounded-circle me-1" alt="">
                                                                    <a href="javascript: void(0);" class="text-reset">{{$item->candidate_name}}</a>
                                                                </td>
                                                                <td class="fw-medium">{{$item->party_name}}</td>
                                                                <td class="fw-medium">{{$item->total_votes}}</td>
                                                                <td class="fw-medium">{{$item->percentage}}</td>

                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                    @empty
                                                    @endforelse
                                                    </tbody><!-- end tbody -->
                                                </table><!-- end table -->
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Results</h4>
                </div>
                <div class="card-body">
                    <div id="ea_chart"></div>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Polling Stations</h4>
                </div>
                <div class="card-body">
                    <div id="voteSummary"></div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section("js")
    <script>
        var deptNames = @json($dept_name_array);
        var deptCounts = @json($dept_count_array);
        var deptBgColors = @json($dept_bgcolor_array);
        var votes = [{{ $voteSummary?->first()['polling_stations_with_votes'] }}, {{ $voteSummary?->first()['polling_stations_without_votes']}}];

        renderDoughnutChart("ea_chart", deptNames, deptCounts, deptBgColors);
        function renderDoughnutChart(elementId, deptNames, deptCounts, deptBgColors) {
            var options = {
                chart: {
                    type: 'pie',
                },
                series: deptCounts,
                labels: deptNames,
                colors: deptBgColors,
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        var total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        return Math.round(val) + '%';
                    }
                },
                legend: {
                    fontFamily: 'Poppins',
                    position: "top",
                },
                fill: {
                    type: 'color',
                    opacity: 1,
                    colors: deptBgColors
                },
                states: {
                    hover: {
                        filter: {
                            type: 'lighten',
                            value: 0.15
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.getElementById(elementId), options);
            chart.render();
        }

        var options2 = {
            chart: {
                type: 'pie',
            },
            series: votes,
            labels: ['Polling Stations with votes', 'Polling Stations without votes'],
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    var total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                    return Math.round(val) + '%';
                }
            },
            legend: {
                fontFamily: 'Poppins',
                position: "top",
            },
            fill: {
                type: 'color',
                opacity: 1,
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.15
                    }
                }
            }
        };

        var chart2 = new ApexCharts(document.getElementById('voteSummary'), options2);
        chart2.render();
    </script>
@endsection
