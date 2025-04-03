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
                    <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                        <label>Client</label>
                        <select class="form-control selectpicker" data-live-search="true" name="filter_assignee" id="filter_assignee">
                            <option selected value="">All Clients</option>
                            @foreach($clients as $cl)
                                <option @if($cl->id == request()->filter_client) selected="selected" @endif value="{{ $cl->id }}">{{ $cl->fullname }}</option>
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
    <script>
        let baseUrl = '/tasks/reports/';
        $(document).ready(function () {
            const fp = flatpickr(".date", {
                mode: "range",
                dateFormat: '{{ env('Date_Format')}}',
                autoclose: true,
                todayHighlight: true,
                defaultDate: ["{{$data['start_date']}}", "{{$data['end_date']}}"],
                onChange: function(selectedDates, dateStr, instance) {
                    const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                    $('#filter_start_date').val(dateArr[0])
                    $('#filter_end_date').val(dateArr[1])
                },
            }); // flatpickr
        });
    </script>
@endsection
