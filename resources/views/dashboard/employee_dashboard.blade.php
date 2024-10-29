@extends('layouts.main')
@section('title', "Dashboard")
@section('page-title', trans('general.welcome') .' '.user()->name)
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-n4 mx-n4">
                <div class="bg-dark-subtle">
                    <div class="card-body pb-0 px-4">
                        <div class="row">
                            <div class="col-6 col-md-3 col-lg-2 mb-3">
                                <img src="{{asset(user()->UserImage)}}"  width='150' class='rounded-circle'>
                            </div>
                            <div class="col-6 col-md-9 col-lg-6 mb-3">
                                <h4 class="fw-bold">{{$employee->full_name}}</h4>
                                <div class="text-muted mb-2">{{$employee->subsidiary->name??""}}</div>
                                <div class="text-muted mb-2">{{$employee->department->department_name}} | {{$employee->designation->designation_name??""}}</div>
                                <p class="text-muted">{{__('Last Login')}}: {{$user->last_login_date}}</p>
                                <p class="text-muted">{{__('My Office Shift')}}:
                                    @if(!$shift_in)
                                        {{__('No Shift Today')}}
                                    @else
                                        {{$shift_in}} To {{$shift_out}}
                                    @endif
                                    ({{$shift_name}})</p>
                                <a class="btn btn-soft-primary" id="my_profile" href="{{route('profile')}}">
                                    <i class="dripicons-user"></i> {{__('Go to Profile')}}
                                </a>
                                <form class="d-inline m1-2" action="{{route('employee_attendance.post',$employee->id)}}" name="set_clocking"
                                      id="set_clocking" autocomplete="off" class="form" method="post" accept-charset="utf-8">
                                    @csrf

                                    <input type="hidden" value="{{$shift_in}}" name="office_shift_in" id="shift_in">
                                    <input type="hidden" value="{{$shift_out}}" name="office_shift_out" id="shift_out">
                                    <input type="hidden" value="" name="in_out_value" id="in_out">

                                    @if(!$employee_attendance || $employee_attendance->clock_in_out=== 0)
                                        <button class="btn btn-success" @if($employee->attendance_type=='ip_based' && $ipCheck!=true) disabled @endif type="submit" id="clock_in_btn"><i class="dripicons-enter"></i> {{__('Clock IN')}}</button>
                                    @else
                                        <button class="btn btn-danger" @if($employee->attendance_type=='ip_based' && $ipCheck!=true) disabled @endif type="submit" id="clock_out_btn"><i class="dripicons-exit"></i> {{__('Clock OUT')}}</button>
                                    @endif
                                    {{-- <br> --}}
                                    @if($employee->attendance_type=='ip_based' && $ipCheck!=true) <small class="text-danger"><i>[Please login with your office's internet to clock in or clock out]</i></small> @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Announcement</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($announcements) }}">{{ count($announcements) }}</span></h2>
                            <a href="{{route('announcements.index')}}" class="text-decoration-underline">View All</a>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">My Requests</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($announcements) }}">{{ count($announcements) }}</span></h2>
                            <a href="{{route('announcements.index')}}" class="text-decoration-underline">View All</a>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">My Todo</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{$assigned_projects_count}}">{{$assigned_projects_count}}</span></h2>
                            <a href="{{route('announcements.index')}}" class="text-decoration-underline">View All</a>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">Upcoming Holidays</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($holidays) }}">{{ count($holidays) }}</span></h2>
                            <a href="{{route('announcements.index')}}" class="text-decoration-underline">View All</a>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-4 col-lg-4">
            <div class="card card-height-100">
                <div class="card-header border-bottom-dashed align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Todo List ({{$assigned_projects_count}})</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 364px;" class="p-3">
                        <table class="table">
                            <tbody>
                            @foreach($assigned_projects as $project)
                                <tr>
                                    <td>
                                        <a href="{{route('projects.show',$project->project->id)}}"><h5>{{$project->project->title}}</h5></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xxl-4 col-lg-4">
            <div class="card card-height-100">
                <div class="card-header border-bottom-dashed align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">My Requests</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body p-0">
                    <div data-simplebar style="max-height: 364px;" class="p-3">
                        <div class="acitivity-timeline acitivity-main">
                            @forelse($activities as $log)
                                <div class="acitivity-item py-3 d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset($log->user->UserImage) }}" alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ ucfirst($log->logAction->name) }}</h6>
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

        <div class="col-xxl-4 col-lg-4">
            <div class="card card-height-100">
                <div class="card-header border-bottom-dashed align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Announcements</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body p-0">
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
            </div>
        </div><!-- end col -->
    </div>
@endsection
