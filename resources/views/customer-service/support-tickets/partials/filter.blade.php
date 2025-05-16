<form class="form-horizontal" id="filter_form" method="GET">

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2">
        <div class="row mt-3">
            <!-- Date Range Filter -->
            <div class="col-md-12 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-3">
                <label>Date range</label>
                <div class="input-group">
                    <div class="input-group-text"><i class="las la-calendar"></i></div>
                    <input type="text" id="date_range" class="form-control" placeholder="Select date range" />
                    <input type="hidden" id="filter_start_date" name="filter_start_date" value="{{ old('filter_start_date', $data['filter_start_date'] ?? '') }}">
                    <input type="hidden" id="filter_end_date" name="filter_end_date" value="{{ old('filter_end_date', $data['filter_end_date'] ?? '') }}">
                    <input type="hidden" id="filter_assignee" name="filter_assignee" value="{{ old('filter_assignee', $data['filter_assignee'] ?? '') }}">
                </div>
            </div>

            <!-- Subsidiary Filter -->
            <div class="col-md-3 col-xl-2 col-lg-2 col-sm-4 col-xs-6 mb-3">
                <label>Support Category</label>
                <select name="filter_category" id="filter_category" class="form-control selectpicker">
                    <option value="">All Categories</option>
                    @foreach($data['categories'] as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $data['filter_category'] ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department Filter -->
            <div class="col-md-3 col-xl-2 col-lg-2 col-sm-4 col-xs-6 mb-3">
                <label>Priority</label>
                <select name="filter_priority" id="filter_priority" class="form-control selectpicker">
                    <option value="">All Priorities</option>
                    @foreach($data['priorities'] as $pr)
                        <option value="{{ $pr->id }}" {{ $pr->id == $data['filter_priority'] ? 'selected' : '' }}>
                            {{ $pr->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Filter -->
            <div class="col-md-3 col-xl-2 col-lg-2 col-sm-4 col-xs-6 mb-3">
                <label>Customers</label>
                <select name="filter_customer" id="filter_customer" class="form-control selectpicker">
                    <option value="">All Customers</option>
                    @foreach($customers as $cus)
                        <option value="{{ $cus->id }}" {{ $cus->id == $data['filter_customer'] ? 'selected' : '' }}>
                            {{ $cus->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Filter -->
            <div class="col-md-3 col-xl-2 col-lg-2 col-sm-4 col-xs-6 mb-3">
                <label>Statuses</label>
                <select name="filter_status" id="filter_property" class="form-control selectpicker">
                    <option value="">All Statuses</option>
                    @foreach($data['statuses'] as $pro)
                        <option value="{{ $pro->id }}" {{ $pro->id == $data['filter_status'] ? 'selected' : '' }}>
                            {{ $pro->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Submit Button -->
            <div class="col-xl-1 col-lg-1 col-md-12 col-sm-1 col-xs-12 pl-md-3 mt-4">
                <button type="button" id="filterSubmit" class="btn btn-primary custom-btn-small filter_submit">Go</button>
            </div>
        </div>
    </div>
</form>
