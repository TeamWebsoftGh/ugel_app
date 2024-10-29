<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="card-title">
                    Filter
                </h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="filter_form" method="GET">
                    <div class="row mt-3">
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Leave Type</label>
                            <select name="filter_leave_type" id="filter_leave_type" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Leave Types</option>
                                @foreach($leave_types as $leave_type)
                                    <option @if($leave_type->id == request()->filter_leave_type) selected="selected" @endif value="{{$leave_type->id}}">{{$leave_type->leave_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Leave Year</label>
                            <select name="filter_leave_year" id="filter_leave_year" data-live-search="true" class="form-control selectpicker">
                                @forelse($leave_years as $year)
                                    <option value="{{$year}}" @if($year->id == request()->filter_leave_year) selected @endif>{{$year}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Subsidiary</label>
                            <select name="filter_subsidiary" id="filter_subsidiary" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Subsidiaries</option>
                                @foreach($subsidiaries as $subsidiary)
                                    <option @if($subsidiary->id == request()->filter_subsidiary) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Department</label>
                            <select name="filter_department" id="filter_department" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option @if($department->id == request()->filter_department) selected="selected" @endif value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Branch</label>
                            <select name="filter_branch" id="filter_branch" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option @if($branch->id == request()->filter_branch) selected="selected" @endif value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Employee</label>
                            <select class="form-control selectpicker" data-live-search="true" name="filter_employee" id="filter_employee">
                                <option selected value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option @if($employee->id == request()->filter_employee) selected="selected" @endif value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-1 col-xl-1 pl-md-3 mt-4">
                            <button type="button" name="btn" id="filterSubmit" title="Click to filter" class="filter_submit btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


