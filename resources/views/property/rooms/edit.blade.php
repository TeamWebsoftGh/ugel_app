<form method="POST" id="room" action="{{ route('rooms.store') }}">
    @csrf
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    <input type="hidden" id="_id" name="id" value="{{$room->id}}">
    <h5>Property Details</h5>
    <div class="row">
        <x-form.input-field name="property_id" label="Property" type="select" :options="$properties->pluck('property_name', 'id')" :value="$room->propertyUnit->property_id" required />
        <x-form.input-field name="property_unit_id" label="Property Unit" type="select" :options="[]" :value="$room->property_unit_id" required />
        <x-form.input-field name="room_name" label="Room Name" type="text" placeholder="Enter Room Name" :value="$room->room_name" required />
        <x-form.input-field name="bed_count" label="Bed Count" type="text" placeholder="Enter Bed Count" :value="$room->bed_count" required />
        <x-form.input-field name="floor" label="Floor" type="text" placeholder="Enter Floor" :value="$room->floor" required />
        <x-form.input-field name="has_ac" label="Has Ac" type="select" :options="['1' => 'Yes', '0' => 'No']" :value="$room->has_ac" required />
        <x-form.input-field name="has_washroom" label="Has Washroom?" type="select" :options="['1' => 'Yes', '0' => 'No']" :value="$room->has_washroom" required />
        <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$room->is_active" required />
        <x-form.input-field name="description" class="col-md-8" rows="3" label="Description" type="textarea" placeholder="Enter a description" :value="$room->description" />
    </div>
    <div class="form-group col-12">
        @include("shared.save-button")
    </div>
</form>
<script>
    $('.dropify').dropify();
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
        let property = $('#property_id').val();

        // Load dropdowns with existing values on page load
        if ($('#property_id').val()) {
            updateDropdown(
                `/api/clients/common/property-units?filter_property=${$('#property_id').val()}`,
                'property_unit_id',
                'Select Property',
                property
            );
        }

        // Update dropdowns dynamically when user changes selection
        $('#property_id').change(function() {
            let categoryId = $(this).val();
            updateDropdown(`/api/clients/common/property-units?filter_property=${categoryId}`, 'property_unit_id', 'Select Property');
        });
    });
</script>
