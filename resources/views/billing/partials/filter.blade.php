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
                        <input class="form-control" id="filter_start_date" type="hidden" name="filter_start_date" value="{{request()->filter_start_date}}">
                        <input class="form-control" id="filter_end_date" type="hidden" name="filter_end_date" value="{{request()->filter_end_date}}">
                        <div class="col-md-12 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-3">
                            <label>Date range</label>
                            <div class="input-group">
                                <div class="input-group-text"><i class="las la-calendar"></i></div>
                                <input type="text" class="form-control date" />
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Company</label>
                            <select name="filter_subsidiary" id="filter_subsidiary" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Companies</option>
                                @foreach($companies as $subsidiary)
                                    <option @if($subsidiary->id == request()->filter_company) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Service Type</label>
                            <select name="filter_service_type" id="filter_department" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Service Types</option>
                                @foreach($service_types as $department)
                                    <option @if($department->id == request()->filter_service_type) selected="selected" @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-1 col-xl-1 pl-md-3 mt-4">
                            <button type="submit" name="btn" id="filterSubmit" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


