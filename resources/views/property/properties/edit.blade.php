<form method="POST" id="property" action="{{ route('properties.store') }}">
    @csrf
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    <input type="hidden" id="_id" name="id" value="{{$property->id}}">
    <h5>Property Details</h5>
    <div class="row">
        <x-form.input-field name="property_name" label="Property Name" type="text" placeholder="Enter Property Name" :value="$property->property_name" required />
        <x-form.input-field name="property_purpose_id" label="Purpose" type="select" :options="$property_purposes->pluck('name', 'id')" :value="$property->property_purpose_id" required />
        <x-form.input-field name="property_category_id" label="Property Category" type="select" :options="$property_categories->pluck('name', 'id')" :value="$property->propertyType->property_category_id" required />
        <x-form.input-field name="property_type_id" label="Property Type" type="select" :options="$property_types->pluck('name', 'id')" :value="$property->property_type_id" required />
        <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$property->is_active" required />
        <x-form.input-field name="description" class="col-md-12" label="Description" type="textarea" placeholder="Enter a description" :value="$property->description" />

        <h5>Property Location</h5>
        <x-form.input-field name="country_id" label="Country" type="select" :options="$countries->pluck('name', 'id')" :value="$property->city->region->country_id" required id="country_id" />
        <x-form.input-field name="region_id" label="Region/State" type="select" :options="[]" :value="$property->city->region_id" required />
        <x-form.input-field name="city_id" label="City" type="select" :options="[]" :value="$property->city_id" required />
        <x-form.input-field name="physical_address" label="Property Address" type="text" placeholder="Enter Address" :value="$property->physical_address" />
        <x-form.input-field name="google_map" label="Google Map" type="text" placeholder="Enter Google Map" :value="$property->google_map" />
    </div>
    <div class="form-group col-12">
        @include("shared.save-button")
    </div>
</form>
<script>
    function updateDropdown(url, targetDropdown, defaultOption = 'Select an option', selectedValue = null) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.status_code === '000') {
                    let dropdown = $('#' + targetDropdown);
                    dropdown.empty();
                    dropdown.append(`<option value="">${defaultOption}</option>`);

                    $.each(response.data, function(index, item) {
                        let isSelected = selectedValue && selectedValue == item.id ? 'selected' : '';
                        dropdown.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
                    });

                    dropdown.selectpicker('refresh');
                } else {
                    console.error("Error fetching data:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    $(document).ready(function() {
        let propertyType = $('#property_type_id').val();
        let regionId = '{{$property->city->region_id}}';
        let cityId = '{{$property->city_id}}';

        // Load dropdowns with existing values on page load
        if ($('#property_category_id').val()) {
            updateDropdown(
                `/api/clients/common/property-types?filter_property_category=${$('#property_category_id').val()}`,
                'property_type_id',
                'Select Property Type',
                propertyType
            );
        }

        if ($('#country_id').val()) {
            updateDropdown(
                `/api/clients/common/regions?filter_country=${$('#country_id').val()}`,
                'region_id',
                'Select Region',
                regionId
            );
        }

        if (regionId) {
            updateDropdown(
                `/api/clients/common/cities?filter_region=${$('#region_id').val()}`,
                'city_id',
                'Select City',
                cityId
            );
        }

        // Update dropdowns dynamically when user changes selection
        $('#property_category_id').change(function() {
            let categoryId = $(this).val();
            updateDropdown(`/api/clients/common/property-types?filter_property_category=${categoryId}`, 'property_type_id', 'Select Property Type');
        });

        $('#country_id').change(function() {
            let countryId = $(this).val();
            updateDropdown(`/api/clients/common/regions?filter_country=${countryId}`, 'region_id', 'Select Region');
        });

        $('#region_id').change(function() {
            let regionId = $(this).val();
            updateDropdown(`/api/clients/common/cities?filter_region=${regionId}`, 'city_id', 'Select City');
        });
    });
</script>
