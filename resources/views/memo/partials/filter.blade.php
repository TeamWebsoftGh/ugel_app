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
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                <label>Constituencies</label>
                <select name="filter_subsidiary" id="filter_subsidiary" data-live-search="true" class="form-control selectpicker">
                    <option selected value="">All Constituencies</option>
                    @foreach($constituencies as $subsidiary)
                        <option @if($subsidiary->id == request()->filter_subsidiary) selected="selected" @endif value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                <label>Electoral Area</label>
                <select name="filter_department" id="filter_department" data-live-search="true" class="form-control selectpicker">
                    <option selected value="">All Electoral Areas</option>
                    @foreach($electoral_areas as $department)
                        <option @if($department->id == request()->filter_department) selected="selected" @endif value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                <label>Polling Station</label>
                <select name="filter_branch" id="filter_branch" data-live-search="true" class="form-control selectpicker">
                    <option selected value="">All Polling Stations</option>
                    @foreach($polling_stations as $loc)
                        <option @if($loc->id == request()->filter_branch) selected="selected" @endif value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-1 col-lg-1 col-md-12 col-sm-1 col-xs-12 pl-md-3 mt-4">
                <button type="submit" name="btn" id="filterSubmit" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
            </div>
        </div>
    </div>
</form>
