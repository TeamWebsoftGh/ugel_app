@extends('layouts.main')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title mb-3 flex-grow-1 text-start"></h6>
                    <div class="mb-2">
                        <lord-icon src="https://cdn.lordicon.com/kbtmbyzy.json" trigger="loop"
                                   colors="primary:#405189,secondary:#02a8b5" style="width:50px;height:50px">
                        </lord-icon>
                    </div>
                    <h3 class="mb-1">{{Carbon\Carbon::parse($ticket->created_at)->diffForHumans()}}</h3>
                    <h5 class="fs-12 mb-4"></h5>
                </div>
            </div><!--end card-->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-card">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <td class="fw-medium">Ticket #</td>
                                <td>{{$ticket->ticket_code}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Priority</td>
                                <td><span class="badge badge-soft-danger">{{$ticket->priority->name}}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Status</td>
                                <td><span class="badge badge-soft-secondary">{{$ticket->status}}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Last Updated</td>
                                <td>{{$ticket->updated_at}}</td>
                            </tr>
                            </tbody>
                        </table><!--end table-->
                    </div>
                </div>
            </div><!--end card-->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <h6 class="card-title mb-0 flex-grow-1">Created By</h6>
                    </div>
                    <ul class="list-unstyled vstack gap-3 mb-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{asset($ticket->owner?->UserImage)}}" alt="" class="avatar-xs rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1"><a href="javascript:void(0)">{{$ticket->owner?->fullname}}</a></h6>
                                    <p class="text-muted mb-0">{{$ticket->client->owner->fullname}}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill text-muted me-2 align-bottom"></i>View</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="d-flex mb-3">
                        <h6 class="card-title mb-0 flex-grow-1">Assigned To</h6>
                    </div>
                    <ul class="list-unstyled vstack gap-3 mb-0">
                        @forelse($ticket->assignees as $assignee)
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{asset($assignee->UserImage)}}" alt="" class="avatar-xs rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1"><a href="javascript:void(0)">{{$assignee->fullname}}</a></h6>
                                        <p class="text-muted mb-0">{{$assignee->role_name}}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill text-muted me-2 align-bottom"></i>View</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
            </div><!--end card-->
        </div><!---end col-->
        <div class="col-xl-9 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">
                        <h5 class="mb-2 fw-semibold text-uppercase">{{$ticket->subject}}</h5>
                        <div class="border-top border-top-dashed mt-4">
                            <h5 class="mt-3">Ticket Note</h5>
                            {{$ticket->ticket_note}}
                            @if($ticket->status == "opened" || $ticket->status == "reopened")
                            <form class="mt-4" method="POST" id="updateForm" action="{{route("support-tickets.store")}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$ticket->id}}">
                                <input type="hidden" name="update_request" value="1">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <label for="exampleFormControlTextarea1" class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control border-light" @if(!in_array(user()->id, $assigneeIds)) disabled @endif id="remarks" rows="3" placeholder="Enter remarks">{{$ticket->remarks}}</textarea>
                                        <span class="input-note text-danger" id="error-remarks"> </span>
                                        @error('remarks')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div><!--end col-->

                                    @if(in_array(user()->id, $assigneeIds) || user()->can('update-support-tickets'))
                                        <div class="col-lg-6">
                                            <label for="exampleFormControlTextarea1" class="form-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option @selected("opened" == $ticket->status) value="opened">Opened</option>
                                                <option @selected("closed" == $ticket->status) value="closed">Closed</option>
                                                <option @selected("cancelled" == $ticket->status) value="cancelled">Cancelled</option>
                                            </select>
                                            <span class="input-note text-danger" id="error-status"> </span>
                                            @error('status')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                        </div><!--end col-->
                                    @endif
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary"> Save changes</button>
                                    </div>
                                </div><!--end row-->
                            </form>
                            @else
                                <h5 class="mt-3">Ticket Remarks</h5>
                                {{$ticket->remarks??"N/A"}}
                            @endif
                        </div>
                    </div>
                </div>
            </div><!--end card-->
            <div class="card" id="task-details">
                <div class="card-header">
                    <div>
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#overview-1" role="tab">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#home-1" role="tab">
                                    Comments ({{$comments->count()}})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#messages-1" role="tab">
                                    Files ({{count($files)}})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#logs-1" role="tab">
                                    Logs
                                </a>
                            </li>
                        </ul><!--end nav-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview-1" role="tabpanel">
                            <h6 class="mb-3 fw-semibold text-uppercase">Ticket Overview</h6>
                            <div data-simplebar style="max-height: 380px;">
                                <p>{!! $ticket->description !!}</p>
                            </div>
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="home-1" role="tabpanel">
                            @include("customer-service.support-tickets.partials.comment")
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="messages-1" role="tabpanel">
                            @include("customer-service.support-tickets.partials.file")
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="logs-1" role="tabpanel">
                            @include("customer-service.support-tickets.partials.logs")
                        </div><!--edn tab-pane-->
                    </div><!--end tab-content-->
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
@endsection
@section("js")
    @include("layouts.shared.dt-scripts")
    <script src="/assets/js/pages/tasks/show-task.js"></script>
@endsection

