<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">Filter</h4>
    </div>
    <div class="card-body">
        <form class="form-horizontal" id="filter_form" method="GET">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2">
                <div class="row mt-3">
                    <div class="col-md-12 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-3">
                        <label>Date range</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="las la-calendar"></i></div>
                            <input type="text"
                                   id="date_range"
                                   class="form-control"
                                   placeholder="Select date range"
                                   data-start="{{ request('filter_start_date') }}"
                                   data-end="{{ request('filter_end_date') }}" />
                            <input type="hidden" id="filter_start_date" name="filter_start_date" value="{{ old('filter_start_date', $data['filter_start_date'] ?? '') }}">
                            <input type="hidden" id="filter_end_date" name="filter_end_date" value="{{ old('filter_end_date', $data['filter_end_date'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                        <label>Client</label>
                        <select class="form-control selectpicker" data-live-search="true" name="filter_client" id="filter_client">
                            <option selected value="">All Clients</option>
                            @foreach($customers as $cl)
                                <option @if($cl->id == request()->filter_client) selected="selected" @endif value="{{ $cl->id }}">{{ $cl->fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                        <label>Properties</label>
                        <select class="form-control selectpicker" data-live-search="true" name="filter_property" id="filter_property">
                            <option selected value="">All Properties</option>
                            @foreach($properties as $pr)
                                <option @if($pr->id == request()->filter_property) selected="selected" @endif value="{{ $pr->id }}">{{ $pr->property_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-3">
                        <label>Status</label>
                        <select class="form-control" data-live-search="true" name="filter_status" id="filter_status">
                            <option value="" selected>All status</option>
                            <option value="pending" @if(request()->filter_status == "pending") selected @endif>Pending</option>
                            <option value="partial" @if(request()->filter_status == "partial") selected @endif>Partial</option>
                            <option value="paid" @if(request()->filter_status == "paid") selected @endif>Paid</option>
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
    @include("layouts.shared.dt-scripts")
@endsection
