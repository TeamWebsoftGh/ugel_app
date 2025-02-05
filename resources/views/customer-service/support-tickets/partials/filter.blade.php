<form class="form-horizontal" id="filter_form" method="GET">
    <input class="form-control" id="filter_start_date" type="hidden" name="filter_start_date" value="{{request()->filter_start_date}}">
    <input class="form-control" id="filter_end_date" type="hidden" name="filter_end_date" value="{{request()->filter_end_date}}">
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
                    <label>Company</label>
                    <select name="filter_subsidiary" id="filter_subsidiary" data-live-search="true" class="form-control selectpicker">
                        <option selected value="">All Companies</option>
                        @foreach($companies as $subsidiary)
                            <option @if($subsidiary->id == request()->filter_company) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                    <label>User</label>
                    <select class="form-control selectpicker" data-live-search="true" name="filter_assignee" id="filter_assignee">
                        <option selected value="">All Users</option>
                        @foreach($users as $employee)
                            <option @if($employee->id == request()->filter_assignee) selected="selected" @endif value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-3">
                <label>Status</label>
                <select class="form-control selectpicker" data-live-search="true" name="filter_status" id="filter_status">
                    <option value="" selected>All status</option>
                    <option @selected("opened" == request()->filter_status) value="opened">Opened</option>
                    <option @selected("closed" == request()->filter_status) value="closed">Closed</option>
                    <option @selected("cancelled" == request()->filter_status) value="cancelled">Cancelled</option>
                    <option @selected("reopened" == request()->filter_status) value="cancelled">Reopened</option>
                </select>
            </div>
            <div class="col-xl-1 col-lg-1 col-md-12 col-sm-1 col-xs-12 pl-md-3 mt-4">
                <button type="submit" name="btn" id="filterSubmit" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
            </div>
        </div>
    </div>
</form>
