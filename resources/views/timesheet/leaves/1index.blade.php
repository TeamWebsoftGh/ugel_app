@extends('layout.admin.main')
@section('title', 'Employee Leave')
@section('page-title', 'Employee Leave Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('leaves.index')}}">Employee Leaves</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="wrapper count-title text-center">
                <a href="javascript:void(0)">
                    <div class="name"><strong class="purple-text">{{ __('Total Leaves') }}</strong>
                    </div>
                    <div class="count-number employee-count">{{$annual->allocated_days}}</div>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="wrapper count-title text-center">
                <a href="javascript:void(0)">
                    <div class="name"><strong class="purple-text">{{ __('Total Spent') }}</strong>
                    </div>
                    <div class="count-number employee-count">{{$annual->spent_days}}</div>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="wrapper count-title text-center">
                <a href="javascript:void(0)">
                    <div class="name"><strong class="purple-text">{{ __('Total Remaining') }}</strong>
                    </div>
                    <div class="count-number employee-count">{{$annual->outstanding_days}}</div>
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Leave Details</h4>
                    <p class="card-subtitle mb-4"></p>
                    <div id="leave-content">
                        <form method="POST">
                            @csrf
                            <input type="hidden" data-ignore="1" name="id" value="{{$leave->id}}">
                            <div class="row">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Employee')}}</label>
                                    <input type="text" class="form-control" disabled value="{{$employee->fullname}}">
                                    <span class="input-note text-danger" id="error-employee_id"> </span>
                                    @error('employee_id')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <input type="hidden" data-ignore="1" name="employee_id" value="{{$leave->employee_id}}">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Leave Type')}} <span class="text-danger">*</span></label>
                                    <input type="text" readonly class="form-control" value="{{$leave->LeaveType->leave_type_name}}">
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Total Days')}} <span class="text-danger">*</span></label>
                                    <input type="number" disabled min="1" name="total_days" id="total_days" class="form-control" value="{{old("total_days",$leave->total_days)}}">
                                    <span class="input-note text-danger" id="error-total_days"> </span>
                                    @error('total_days')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Start Date')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="start_date" readonly id="start_date" class="form-control" value="{{old("start_date",$leave->start_date)}}">
                                    <span class="input-note text-danger" id="error-start_date"> </span>
                                    @error('start_date')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('End Date')}}</label>
                                    <input type="text" name="end_date" readonly="readonly" id="end_date" class="form-control end_date" value="{{old("end_date",$leave->end_date)}}">
                                    <span class="input-note text-danger" id="error-end_date"> </span>
                                    @error('end_date')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Resumption Date')}}</label>
                                    <input type="text" name="resumption_date" readonly id="resumption_date" class="form-control resumption_date" value="{{old("resumption_date", $leave->resumption_date)}}">
                                    <span class="input-note text-danger" id="error-resumption_date"> </span>
                                    @error('resumption_date')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Reliever')}}  <span class="text-danger">*</span></label>
                                    <input type="hidden" name="reliever_id_hidden" value="{{ $employee->reliever_id }}"/>
                                    <select name="reliever_id" id="reliever_id" class="form-control selectpicker"
                                            data-live-search="true"
                                            title="{{__('Selecting',['key'=>__('Reliever')])}}...">
                                        @foreach($employees->except([$employee->id]) as $emp)
                                            <option value="{{$emp->id}}">{{$emp->FullName}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-note text-danger" id="error-reliever_id"> </span>
                                    @error('reliever_id')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-8 col-12 col-lg-6 form-group">
                                    <label for="handover_note">{{__("Handover Note")}}</label>
                                    <textarea class="form-control" id="handover_note" readonly name="handover_note"
                                              rows="2">{{old("handover_note",$leave->handover_note)}}</textarea>
                                    <br/>
                                    @forelse($leave->attachments as $at)
                                        <a href="{{asset($at->file_path)}}">{{$at->file_name}}</a>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{trans('file.Status')}}</label>
                                    <input type="text" readonly class="form-control" value="{{$leave->status}}">
                                </div>
                                <div class="col-md-8 col-12 col-lg-6 form-group">
                                    <label for="leave_reason">{{__("Reason")}}</label>
                                    <textarea readonly class="form-control" id="leave_reason" name="leave_reason"
                                              rows="2">{{$leave->leave_reason}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12">
                            </div>
                        </form>
                        @if($leave->status == "pending" && $leave->can_approve)
                            @include("shared.approval", ['workflow_request_detail' => request()->q])
                        @endif
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('public/js/pages/leave.js') }}"></script>
    <script>
        $('#reliever_id').selectpicker('val', {{old("reliever_id", $leave->reliever_id)}});
    </script>
@endsection
