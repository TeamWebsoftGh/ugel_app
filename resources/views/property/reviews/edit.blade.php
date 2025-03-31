<form method="POST" id="room" action="{{ route('reviews.store') }}">
    @csrf
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    <input type="hidden" id="_id" name="id" value="{{$item->id}}">
    <h5>Property Details</h5>
    <div class="row">
        <x-form.input-field
            name="client_id"
            label="Student/Customer"
            type="select"
            :options="$customers->pluck('name', 'id')"
            :value="$item->client_id"
            required
        />
        <x-form.input-field name="property_id" label="Property" type="select" :options="$properties->pluck('property_name', 'id')" :value="$item->property_id" required />
        <x-form.input-field name="rating" label="Rating" type="select" :options="['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5']" :value="$item->rating" required />
        <x-form.input-field name="comment" class="col-md-8" rows="3" label="Comment" type="textarea" placeholder="Enter a Comment" :value="$item->comment" required/>
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
