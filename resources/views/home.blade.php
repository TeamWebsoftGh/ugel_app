@extends('layouts.main')
@section('title', "Welcome")
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
                                <h4 class="fs-16 mb-1">Welcome {{user()->first_name}}!</h4>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <div class="row g-3 mb-0 align-items-center">
                                    <!--end col-->
                                    <div class="col-auto">
                                        <a href="" class="btn btn-soft-success"><i
                                                class="ri-task-line align-middle me-1"></i>
                                           My Requests</a>
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
                                <h4 class="card-title mb-0 flex-grow-1">Recent Bookings ({{$bookings->count()}})</h4>
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
                                        @forelse($bookings->take(25)->get() as $booking)
                                            <li class="list-group-item ps-0">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-auto">
                                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                                            <div class="text-center">
                                                                <h5 class="mb-0">{{ date('jS', strtotime($booking->booking_date)) }}</h5>
                                                                <div class="text-muted">{{ date('M', strtotime($booking->booking_date)) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h5 class="text-muted mt-0 mb-1 fs-12 fw-semibold">{{ $booking->client->client_number }}</h5>
                                                        <a href="#" class="text-reset fs-14 mb-0">{{ $booking->client->fullname }}</a>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript:void(0);" class="d-inline-block"
                                                                   data-bs-toggle="tooltip"
                                                                   data-bs-placement="top"
                                                                   title="{{ $booking->fullname }}">
                                                                    <img src="{{ asset($booking->user->Userimage ?? 'assets/images/user.png') }}"
                                                                         alt=""
                                                                         class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-muted">No bookings available.</li>
                                        @endforelse
                                    </ul>

                                </div>
                            </div><!-- end card body -->
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-8 order-xxl-0 order-first">
                        <div class="d-flex flex-column h-100">
                            <div class="row h-100">
                                <div class="col-lg-3 col-md-4">
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
                                                    <a href="{{route('admin.customers.index')}}" class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                        Customers</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$p_customer_count}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-lg-3 col-md-4">
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
                                                    <a href="{{route('bookings.index')}}" class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                        Bookings</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$bookings->count()}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-lg-3 col-md-4">
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
                                                    <a href="{{route('support-tickets.index')}}" class="text-uppercase fw-semibold fs-12 text-muted mb-1">Complaints</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$ticket_count}}">0</span></h4>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                            <i class="ri-home-2-fill align-middle"></i>
                                                        </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <a href="{{route("court-cases.index")}}" class="text-uppercase fw-semibold fs-12 text-muted mb-1">Court Cases</a>
                                                    <h4 class=" mb-0"><span class="counter-value" data-target="{{$leave_count}}">0</span></h4>
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
                                            <h4 class="card-title flex-grow-1 mb-0">Active Customers</h4>
                                        </div><!-- end cardheader -->
                                        <div class="card-body">
                                            <div class="table-responsive table-card">
                                                <table class="table dashboard-table table-centered align-middle">
                                                    <thead class="bg-light text-muted">
                                                    <tr>
                                                        <th scope="col">Full Name</th>
                                                        <th scope="col">Customer Number</th>
                                                        <th scope="col">Phone Number</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Client type</th>
                                                    </tr><!-- end tr -->
                                                    </thead><!-- thead -->
                                                    <tbody>
                                                    @forelse($customers->slice(0, 8) as $employee)
                                                        <tr>
                                                            <td>
                                                                <a href="javascript: void(0);" class="text-reset">{{$employee->fullname}}</a>
                                                            </td>
                                                            <td class="fw-medium">{{$employee->client_number??"N/A"}}</td>
                                                            <td class="fw-medium">{{$employee->phone_number}}</td>
                                                            <td class="text-muted">{{$employee->email}}</td>
                                                            <td class="text-muted">{{$employee->clientType->name}}</td>
                                                        </tr><!-- end tr -->
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
        <div class="col-xxl-4 col-lg-4">
            <div class="card card-height-100">
                <div class="card-header border-bottom-dashed align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Activity</h4>
                    <div class="flex-shrink-0">
                        <a href="{{route("audit.user_activity")}}" class="btn btn-soft-primary btn-sm">
                            View All Activity
                        </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 364px;" class="p-3">
                        <div class="acitivity-timeline acitivity-main">
                            @forelse($activities as $log)
                                <div class="acitivity-item py-3 d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset(isset($activity->user)? $activity->user->UserImage:'/assets/images/user.png') }}" alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ isset($activity->user)? $activity->user->fullname:'System' }}</h6>
                                        <p class="text-muted mb-1">{{$log->description}}</p>
                                        <small class="mb-0 text-muted">{{$log->created_at}}</small>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xxl-4 col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Customer Types</h4>
                </div><!-- end card-header -->
                <div class="card-body p-0">
                    <canvas id="client_chart" class="chartjs-chart" data-colors="[&quot;--vz-primary&quot;, &quot;--vz-light&quot;]" width="708" height="400"></canvas>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Notifications ({{ count($announcements) }})</h4>
                    <div>
                        <a href="{{route('bulk-sms.index')}}" class="btn btn-soft-primary btn-sm">
                            View all
                        </a>
                    </div>
                </div><!-- end card-header -->

                <div class="card-body">
                    <div data-simplebar style="max-height: 364px;" class="p-3">
                        <div class="acitivity-timeline acitivity-main">
                            @forelse($announcements as $announcement)
                                <div class="d-flex mt-4">
                                    <div class="flex-shrink-0">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base"><a href="#" class="text-reset">{{$announcement->title}}</a></h6>
                                        <p class="text-muted fs-12 mb-0">{{$announcement->end_date}} <i class="mdi mdi-circle-medium align-middle mx-1"></i>09:22 AM</p>
                                    </div>
                                </div><!-- end -->
                            @empty
                                Nothing yet
                            @endforelse
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
@endsection
@section("js")
    <script>
        var isdoughnutchart = document.getElementById("client_chart");
        doughnutChartColors = getChartColorsArray("client_chart"), doughnutChartColors && (lineChart = new Chart(isdoughnutchart, {
            type: "doughnut",
            data: {
                labels: [@json($dept_name_array)],
                datasets: [{
                    data: [@json($dept_count_array)],
                    backgroundColor: @json($dept_bgcolor_array),
                    hoverBackgroundColor: @json($dept_hover_bgcolor_array),
                    hoverBorderColor: "#fff"
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: "Poppins"
                            }
                        }
                    }
                }
            }
        }));
    </script>
@endsection
