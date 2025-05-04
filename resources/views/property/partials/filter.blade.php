<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="card-title">
                    Filter
                </h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="GET" id="filter_form">
                    <div class="row mt-3">
                        <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                            <label>Property Category</label>
                            <select name="filter_property_category" id="filter_property_category" data-live-search="true" class="form-control selectpicker">
                                <option selected value="">All Property Categories</option>
                                @foreach($property_categories as $cat)
                                    <option @if($cat->id == request()->filter_property_category) selected="selected" @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(isset($type))
                            <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                                <label>Property Type</label>
                                <select name="filter_property_type" id="filter_property_type" data-live-search="true" class="form-control selectpicker">
                                    <option selected value="">All Property Types</option>
                                    @foreach($property_types as $cat)
                                        <option @if($cat->id == request()->filter_property_type) selected="selected" @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if(isset($property))
                            <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                                <label>Property</label>
                                <select name="filter_property" id="filter_property" data-live-search="true" class="form-control selectpicker">
                                    <option selected value="">All Properties</option>
                                    @foreach($all_properties as $prop)
                                        <option @if($prop->id == request()->filter_property) selected="selected" @endif value="{{ $prop->id }}">{{ $prop->property_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if(isset($unit))
                            <div class="col-md-4 col-lg-3 col-xl-2 mb-3">
                                <label>Property Unit</label>
                                <select name="filter_property_unit" id="filter_property_unit" data-live-search="true" class="form-control selectpicker">
                                    <option selected value="">All Property Units</option>
                                </select>
                            </div>
                        @endif

                        <div class="col-md-2 col-lg-1 col-xl-1 pl-md-3 mt-4">
                            <button type="button" name="btn" id="filter_submit" title="Click to filter" class="btn btn-primary filter_submit custom-btn-small mt-0 mr-0">Go</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Update dropdowns dynamically when user changes selection
    $('#filter_property_category').change(function() {
        let categoryId = $(this).val();
        updateDropdown(`/api/clients/common/property-types?filter_property_category=${categoryId}`, 'filter_property_type', 'All Property Types');
    });
    $('#filter_property_type').change(function() {
        let categoryId = $(this).val();
        updateDropdown(`/api/clients/common/properties?filter_property_type=${categoryId}`, 'filter_property', 'All Properties');
    });
    $('#filter_property').change(function() {
        let categoryId = $(this).val();
        updateDropdown(`/api/clients/common/property-units?filter_property=${categoryId}`, 'filter_property_unit', 'All Property Units');
    });
</script>
