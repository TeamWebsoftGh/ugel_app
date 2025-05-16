<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">Filter</h4>
    </div>
    <div class="card-body">
        <form class="form-horizontal" id="filter_form" method="GET">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2">
                <div class="row mt-3">
                    <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-3">
                        <label>Property Type</label>
                        <select class="form-control selectpicker" data-live-search="true" name="filter_property_type" id="filter_property_type">
                            <option selected value="">All Property Types</option>
                            @foreach($property_types as $cl)
                                <option @if($cl->id == request()->filter_property_type) selected="selected" @endif value="{{ $cl->id }}">{{ $cl->name }}</option>
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
