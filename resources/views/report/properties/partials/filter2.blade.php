<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">Filter</h4>
    </div>
    <div class="card-body">
        <form class="form-horizontal" id="filter_form" method="GET">
            <input id="filter_start_date" type="hidden" name="filter_start_date" value="{{request()->filter_start_date}}">
            <input id="filter_end_date" type="hidden" name="filter_end_date" value="{{request()->filter_end_date}}">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2">
                <div class="row mt-3">
                    <div class="col-md-12 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-3">
                        <label>Date range</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="las la-calendar"></i></div>
                            <input type="text" class="form-control date" />
                        </div>
                    </div>
                    @if(!isset($user))
                        <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                            <label>Subsidiary</label>
                            <select name="filter_subsidiary" id="filter_subsidiary" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Subsidiaries</option>
                                @foreach($subsidiaries as $subsidiary)
                                    <option @if($subsidiary->id == request()->filter_subsidiary) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                            <label>Department</label>
                            <select name="filter_department" id="filter_department" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option @if($department->id == request()->filter_department) selected="selected" @endif value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                            <label>Assignee</label>
                            <select class="form-control selectpicker" data-live-search="true" name="filter_assignee" id="filter_assignee">
                                <option selected value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option @if($employee->id == request()->filter_assignee) selected="selected" @endif value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-3">
                        <label>Status</label>
                        <select class="form-control selectpicker" data-live-search="true" name="filter_status" id="filter_status">
                            <option value="" selected>All status</option>
                            @foreach($task_statuses as $status)
                                <option @if($status->id == request()->filter_status) selected="selected" @endif value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-1 col-xs-12 pl-md-3 mt-4">
                        <button type="submit" name="btn" id="filterSubmit" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@section("js")
    @include("layouts.portal.shared.dt-scripts")
    <script>
        let baseUrl = '/tasks/reports/';
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                dateFormat: 'F-Y',
                autoclose: true,
                todayHighlight: true,
                defaultDate: ["{{$data['end_date']}}"],
                onChange: function(selectedDates, dateStr, instance) {
                    const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                    $('#filter_start_date').val(dateArr[0])
                    $('#filter_end_date').val(dateArr[1])
                },
            }); // flatpickr
        });
    </script>
@endsection
