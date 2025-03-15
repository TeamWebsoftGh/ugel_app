<form method="POST" id="property-info-form" action="{{ route('properties.store') }}">
    @csrf
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    <input type="hidden" id="_id" name="id" value="{{$property->id}}">
    <h5>Property Details</h5>
    <div class="row clearfix">
        <x-form.input-field name="property_name" label="Property Name" type="text" placeholder="Enter Property Name" :value="$property->property_name" required />
        <x-form.input-field name="property_purpose_id" label="Purpose" type="select" :options="$property_purposes->pluck('name', 'id')" :value="$property->property_purpose_id" required />
        <x-form.input-field name="property_category_id" label="Property Category" type="select" :options="$property_categories->pluck('name', 'id')" :value="$property->propertyType->property_category_id" required />
        <x-form.input-field name="property_type_id" label="Property Type" type="select" :options="$property_types->pluck('name', 'id')" :value="$property->property_type_id" required />
        <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$property->is_active" required />
        <x-form.input-field name="description" label="Description" type="textarea" placeholder="Enter a description" :value="$property->description" />

        <h5>Property Location</h5>
        <x-form.input-field name="country_id" label="Country" type="select" :options="$countries->pluck('name', 'id')" :value="$property->city->region->country_id" required id="country_id" />
        <x-form.input-field name="region_id" label="Region/State" type="select" :options="[]" :value="$property->city->region_id" required id="region_id" />
        <x-form.input-field name="city_id" label="City" type="select" :options="[]" :value="$property->city_id" required id="city_id" />
        <x-form.input-field name="physical_address" label="Property Address" type="text" placeholder="Enter Address" :value="$property->physical_address" />
        <x-form.input-field name="google_map" label="Google Map" type="text" placeholder="Enter Google Map" :value="$property->google_map" />
    </div>
    <div class="form-group col-12 d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-light skip-step" data-nexttab="steparrow-unit-info">Skip</button>
        <button type="button" class="btn btn-success save-next" data-form="property-info-form" data-nexttab="steparrow-unit-info">Next <i class="ri-arrow-right-line"></i></button>
    </div>
</form>
