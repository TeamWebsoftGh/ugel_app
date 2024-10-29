@extends('layouts.main')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title mb-3 flex-grow-1 text-start">Deadline</h6>
                    <div class="mb-2">
                        <lord-icon src="https://cdn.lordicon.com/kbtmbyzy.json" trigger="loop"
                                   colors="primary:#405189,secondary:#02a8b5" style="width:50px;height:50px">
                        </lord-icon>
                    </div>
                    <h3 class="mb-1">{{Carbon\Carbon::createFromFormat(env('Date_Format'), $task->due_date)->diffForHumans()}}</h3>
                    <h5 class="fs-12 mb-4"></h5>
                </div>
            </div><!--end card-->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-card">
                        <table class="table mb-0">
                            <tbody>
                            <tr>
                                <td class="fw-medium">Task #</td>
                                <td>{{$task->code}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Priority</td>
                                <td><span class="badge badge-soft-danger">{{$task->priority->name}}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Status</td>
                                <td><span class="badge badge-soft-secondary">{{$task->taskStatus->name}}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Start Date</td>
                                <td>{{$task->start_date}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Due Date</td>
                                <td>{{$task->due_date}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Revenue Target</td>
                                <td>{{$task->revenue_target??"N/A"}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Budget</td>
                                <td>{{$task->budget??"N/A"}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Total Weightage</td>
                                <td>{{$task->total_weightage??"N/A"}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Employee Score</td>
                                <td>{{$task->employee_score??"N/A"}}</td>
                            </tr>
                            </tbody>
                        </table><!--end table-->
                    </div>
                </div>
            </div><!--end card-->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <h6 class="card-title mb-0 flex-grow-1">Assigned To</h6>
                    </div>
                    <ul class="list-unstyled vstack gap-3 mb-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{asset("storage/".$task->assignee->UserImage)}}" alt="" class="avatar-xs rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1"><a href="javascript:void(0)">{{$task->assignee->fullname}}</a></h6>
                                    <p class="text-muted mb-0">{{$task->assignee->designation->designation_name}}</p>
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
                        <h6 class="card-title mb-0 flex-grow-1">Assigned By</h6>
                    </div>
                    <ul class="list-unstyled vstack gap-3 mb-0">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{asset("storage/".$task->createdBy->UserImage)}}" alt="" class="avatar-xs rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1"><a href="javascript:void(0)">{{$task->createdBy->fullname}}</a></h6>
                                    <p class="text-muted mb-0">{{$task->createdBy->designation->designation_name}}</p>
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
                </div>
            </div><!--end card-->
        </div><!---end col-->
        <div class="col-xl-9 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">
                        <h5 class="mb-2 fw-semibold text-uppercase">{{$task->title}}</h5>
                        <div class="border-top border-top-dashed mt-4">
                            <form class="mt-4" method="POST" id="updateForm" action="{{route("tasks.store")}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$task->id}}">
                                <input type="hidden" name="update_request" value="1">
                                <div class="row g-3">
                                    @if($task->status_id == \App\Constants\StatusConstants::PENDING || $task->status_id == \App\Constants\StatusConstants::ONHOLD)
                                        <div class="col-md-12">
                                            <div class="form-check form-switch form-switch-lg" dir="ltr">
                                                <input type="checkbox" name="has_budget" @if(!$task->edit_budget) disabled @endif value="1" class="form-check-input" id="has_budget" @if($task->has_budget) checked @endif>
                                                <label class="form-check-label" for="has_budget">Add Budget/Resource</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 budget_container">
                                            <label for="lead_score-field" class="form-label">Budget</label>
                                            <div class="input-group">
                                                <div class="input-group-text">{{currency()->symbol}}</div>
                                                <input type="number" min="0" @if(!$task->edit_budget) disabled @endif name="budget" value="{{old('budget', $task->budget)}}" class="form-control" />
                                            </div>
                                            @error('budget')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-budget"> </span>
                                        </div>
                                        <div class="form-group col-12 col-md-4 budget_container">
                                            <label for="resources" class="control-label">Resources</label>
                                            <input type="text" @if(!$task->edit_budget) disabled @endif id="resources" name="resources" value="{{old('resources', $task->resources)}}" class="form-control">
                                            @error('resources')
                                            <span class="input-note text-danger">{{ $message }} </span>
                                            @enderror
                                            <span class="input-note text-danger" id="error-resources"> </span>
                                        </div>
                                    @endif
                                    @if($task->status_id == \App\Constants\StatusConstants::SUBMITTED || $task->status_id == \App\Constants\StatusConstants::COMPLETED)
                                    <div class="col-lg-6">
                                        <label for="exampleFormControlTextarea1" class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control border-light" @if($task->assignee_id == user()->id || $task->status_id != \App\Constants\StatusConstants::SUBMITTED) disabled @endif id="remarks" rows="3" placeholder="Enter remarks">{{$task->remarks}}</textarea>
                                        <span class="input-note text-danger" id="error-remarks"> </span>
                                        @error('remarks')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div><!--end col-->
                                    @endif
                                    @if($task->status_id == \App\Constants\StatusConstants::SUBMITTED && user()->id != $task->assignee_id)
                                    <div class="form-group col-12 col-md-4">
                                        <label for="employee_score" class="control-label">Employee Score <span class="text-danger">*</span></label>
                                        <input type="number" min="0" id="employee_score" name="employee_score" value="{{old('employee_score', $task->employee_score)}}" class="form-control">
                                        @error('employee_score')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                        <span class="input-note text-danger" id="error-total_weightage"> </span>
                                    </div>
                                    @endif

                                    <div class="col-12">
                                        @if(user()->id == $task->assignee_id)
                                            @if($task->status_id == \App\Constants\StatusConstants::PENDING && $task->stage == "budget")
                                                <input type="hidden" name="stage" class="form-control" value="employee">
                                                <input type="hidden" name="status_id" class="form-control" value="{{\App\Constants\StatusConstants::ACCEPTED}}">
                                                <button type="submit" class="btn btn-primary">Accept Task</button>
                                            @elseif($task->status_id == \App\Constants\StatusConstants::PENDING && $task->stage == "employee")
                                                <input type="hidden" name="status_id" class="form-control" value="{{\App\Constants\StatusConstants::ACCEPTED}}">
                                                <input type="hidden" name="stage" id="stage" class="form-control" value="employee">
                                                <button type="submit" id="accept_btn" class="btn btn-primary">Accept Task</button>
                                            @elseif($task->status_id == \App\Constants\StatusConstants::INPROGRESS && $task->stage == "employee")
                                                <input type="hidden" name="status_id" class="form-control" value="{{\App\Constants\StatusConstants::SUBMITTED}}">
                                                <button type="submit" class="btn btn-primary">Mark as Complete</button>
                                            @elseif($task->status_id == \App\Constants\StatusConstants::ACCEPTED && $task->stage == "employee")
                                                <input type="hidden" name="status_id" class="form-control" value="{{\App\Constants\StatusConstants::SUBMITTED}}">
                                                <input type="hidden" name="stage" class="form-control" value="supervisor">
                                                <button type="submit" class="btn btn-primary">Mark as Complete</button>
                                            @endif
                                        @else
                                            @if($task->status_id == \App\Constants\StatusConstants::ONHOLD && $task->stage == "budget")
                                                <input type="hidden" name="budget_is_accepted" class="form-control" value="1">
                                                <input type="hidden" name="status_id" class="form-control" value="{{\App\Constants\StatusConstants::PENDING}}">
                                                <button type="submit" name="gh" value="0" class="btn btn-primary">Approve Budget</button>
                                                <a href="javascript:void(0)" onclick="ChangeTaskStatus('Decline Budget', '0', '{{route('tasks.change-status', $task->id)}}')" class="btn btn-danger">Decline Budget</a>
                                            @elseif($task->status_id == \App\Constants\StatusConstants::SUBMITTED)
                                                <input type="hidden" name="status_id" value="{{\App\Constants\StatusConstants::COMPLETED}}">
                                                <button type="submit" class="btn btn-primary">Accept Completed Task</button>
                                            @endif
                                        @endif
                                    </div>
                                </div><!--end row-->
                            </form>
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
                                <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab">
                                    Activities ({{$task->timesheets->count()}})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#home-1" role="tab">
                                    Comments ({{$task->taskComments->count()}})
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
                            <h6 class="mb-3 fw-semibold text-uppercase">Task Overview</h6>
                            <div data-simplebar style="max-height: 380px;">
                                <p>{!! $task->description !!}</p>
                                <h6 class="mb-3 fw-semibold text-uppercase">Objectives</h6>
                                <ul class="ps-3 list-unstyled vstack gap-2">
                                    @forelse($objectives as $obj)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" @if($obj->is_complete) checked @endif id="objective-{{$obj->id}}">
                                                <label class="form-check-label" for="objective-{{$obj->id}}">
                                                    {{$obj->name}}
                                                    @if($obj->user_id == user()->id)
                                                        <a href="javascript:void(0)" onclick="DeleteItem('{{$obj->name}}', '{{route("tasks.objectives.destroy", ['task_id'=>$task->id, 'id' => $obj->id])}}')" class="text-danger"><i class="las la-trash"></i></a>
                                                    @endif
                                                </label>
                                            </div>
                                        </li>
                                    @empty
                                        Nothing yet
                                        @endforelse
                                        </li>
                                </ul>
                                <div id="activity_container" class="mb-3">
                                    @include("portal.tasks.partials.edit-objective")
                                </div>
                                @if(isset($task->anticipated_challenges))
                                    <h6 class="mb-3 fw-semibold text-uppercase">Anticipated Challenges/Opportunities</h6>
                                    <p>{!! $task->anticipated_challenges !!}</p>
                                @endif
                            </div>
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="home-1" role="tabpanel">
                            @include("tasks.partials.comment")
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="messages-1" role="tabpanel">
                            @include("tasks.partials.file")
                        </div><!--end tab-pane-->
                        <div class="tab-pane" id="profile-1" role="tabpanel">
                            @include("tasks.partials.activity")
                        </div><!--edn tab-pane-->
                        <div class="tab-pane" id="logs-1" role="tabpanel">
                            @include("tasks.partials.logs")
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

