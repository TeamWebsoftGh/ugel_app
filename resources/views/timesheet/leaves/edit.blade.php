@extends('layouts.main')
@section('title', 'List of Employee Leaves')
@section('page-title', 'Employee Leaves')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('timesheet.leaves.index')}}">Employee Leaves</a></li>
@endsection

@section('content')
    <div class="card crm-widget">
        <div class="card-body p-0">
            <div class="row row-cols-md-3 row-cols-1">
                <div class="col col-lg border-end">
                    <div class="py-4 px-3">
                        <h5 class="text-muted text-uppercase fs-13">{{ __('Total Leaves') }}</h5>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h2 class="mb-0"><span class="counter-value" data-target="{{$annual->allocated_days}}">{{$annual->allocated_days}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
                <div class="col col-lg border-end">
                    <div class="mt-3 mt-md-0 py-4 px-3">
                        <h5 class="text-muted text-uppercase fs-13">{{ __('Total Used') }} </h5>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h2 class="mb-0"><span class="counter-value" data-target="{{$annual->spent_days}}">{{$annual->spent_days}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
                <div class="col col-lg">
                    <div class="mt-3 mt-lg-0 py-4 px-3">
                        <h5 class="text-muted text-uppercase fs-13">
                            {{ __('Total Remaining') }}
                        </h5>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h2 class="mb-0"><span class="counter-value" data-target="{{$annual->outstanding_days}}">{{$annual->outstanding_days}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
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
                        <form method="POST" action="{{route("timesheet.leaves.store")}}" class="form-horizontal">
                            @csrf
                            <input type="hidden" data-ignore="1" name="id" value="{{$leave->id}}">
                            <div class="row">
                                <input type="hidden" data-ignore="1" name="employee_id" value="{{$employee->id}}">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Employee')}}</label>
                                    <input type="text" class="form-control" disabled value="{{$employee->fullname}}">
                                    <span class="input-note text-danger" id="error-employee_id"> </span>
                                    @error('employee_id')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Leave Type')}} <span class="text-danger">*</span></label>
                                    <select name="leave_type" id="leave_type_id" class="form-control selectpicker " data-live-search="true" data-live-search-style="begins" title='{{__('Leave Type')}}'>
                                        @foreach($leave_types as $leave_type)
                                            <option value="{{$leave_type->id}}">{{$leave_type->leave_type_name}}
                                                ({{$leave_type->allocated_days}} Days)
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-note text-danger" id="error-leave_type"> </span>
                                    @error('leave_type')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Total Days')}} <span class="text-danger">*</span></label>
                                    <input type="number" min="1" name="total_days" id="total_days" class="form-control" value="{{old("total_days",$leave->total_days)}}">
                                    <span class="input-note text-danger" id="error-total_days"> </span>
                                    @error('total_days')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Start Date')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="start_date" id="start_date" class="form-control date" value="{{old("start_date",$leave->start_date)}}">
                                    <span class="input-note text-danger" id="error-start_date"> </span>
                                    @error('start_date')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('End Date')}}</label>
                                    <input type="text" name="end_date" readonly="readonly" id="end_date" class="form-control date" value="{{old("end_date",$leave->end_date)}}">
                                    <span class="input-note text-danger" id="error-end_date"> </span>
                                    @error('end_date')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-6 col-lg-3 form-group">
                                    <label>{{__('Resumption Date')}}</label>
                                    <input type="text" name="resumption_date" readonly id="resumption_date" class="form-control date" value="{{old("resumption_date", $leave->resumption_date)}}">
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
                                    <select name="reliever_id" id="reliever_id" class="form-control selectpicker" title="{{__('Selecting',['key'=>__('Reliever')])}}...">
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
                                    <textarea class="form-control" id="handover_note" name="handover_note"
                                              rows="3">{{old("handover_note",$leave->handover_note)}}</textarea>
                                    <span class="input-note text-danger" id="error-handover_note"> </span>
                                    @error('handover_note')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                @if(user()->can('create-leaves'))
                                    <div class="col-md-4 col-6 col-lg-3 form-group">
                                        <label>{{__('Status')}} <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control selectpicker "
                                                data-live-search="true"
                                                title='{{__('Selecting',['key'=>__('Status')])}}...'>
                                            <option value="submitted">{{__('Submitted')}}</option>
                                            <option value="pending">{{__('Pending')}}</option>
                                            <option value="approved">{{__('Approved')}}</option>
                                            <option value="rejected">{{__('Rejected')}}</option>
                                        </select>
                                        <span class="input-note text-danger" id="error-status"> </span>
                                        @error('status')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="col-md-8 col-12 col-lg-6 form-group">
                                    <label for="leave_reason">{{__("Reason")}}</label>
                                    <textarea class="form-control" id="leave_reason" name="leave_reason"
                                              rows="3">{{$leave->leave_reason}}</textarea>
                                    <span class="input-note text-danger" id="error-leave_reason"> </span>
                                    @error('leave_reason')
                                    <span class="input-note text-danger">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-12">
                                @if($leave->employee_id == user()->id || user()->can("update-leaves"))
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        let baseUrl = '/leaves/' ;
    </script>
    {{--    @include("layout.admin.shared.datatable")--}}
    <script>
        $('#leave_type_id').selectpicker('val', '{{old("leave_type", $leave->leave_type_id)}}');
        $('#reliever_id').selectpicker('val', '{{old("reliever_id", $leave->reliever_id)}}');
        $('#status').selectpicker('val', '{{old("status", $leave->status)}}');
        $(document).ready(function () {
            $('#start_date').change(function (e) {
                const start_date = $(this).val(),
                    duration = $('#total_days').val(),
                    leave_type_id = $('#leave_type').val();
                if($.trim(leave_type_id) === ''){alert('Please select the Leave Type');}
                if ($.trim(duration) === '') {
                    alert('Please specify a duration!');
                } else {
                    $.get(appUrl + 'check-for-holiday-or-weekend',{start_date:start_date, total_days:duration},
                        function (e) {
                            $('#end_date').val(e.end_date);
                            $('#resumption_date').val(e.resumption_date);
                            let date = $('.date');
                            date.datepicker({
                                format: '{{ env('Date_Format_JS')}}',
                                autoclose: true,
                                todayHighlight: true
                            });
                        }
                    );
                }

            });
        });
    </script>
@endsection
