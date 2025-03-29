<form method="POST" id="booking" action="{{ route('bookings.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">

    <div class="row">
        <!-- Type -->
        <x-form.input-field
            name="client_id"
            label="Student/Customer"
            type="select"
            :options="$customers->pluck('name', 'id')"
            :value="$item->client_id"
            required
        />
        <!-- Name -->
        <x-form.input-field
            name="booking_period_id"
            label="Booking Period"
            type="select"
            :options="$booking_periods->pluck('name', 'id')"
            :value="$item->booking_period_id"
        />

        <x-form.input-field
            name="property_id"
            label="Property"
            type="select"
            :options="$properties->pluck('property_name', 'id')"
            :value="$item->property_id"
            required
        />

        <x-form.input-field
            name="property_unit_id"
            label="Property Unit"
            type="select"
            :options="[]"
            :value="$item->property_unit_id"
            required
        />

        <x-form.input-field
            name="room_id"
            label="Room"
            type="select"
            :options="[]"
            :value="$item->room_id"
        />

        <!-- Lease Start Date -->
        <x-form.input-field
            name="sub_total"
            label="Price"
            type="text"
            readonly
            :value="$item->sub_total"
        />

        <!-- Lease Start Date -->
        <x-form.input-field
            name="lease_start_date"
            label="Lease Start Date"
            type="date"
            :value="$item->lease_start_date"
            required
        />

        <!-- Lease End Date -->
        <x-form.input-field
            name="lease_end_date"
            label="Lease End Date"
            type="date"
            :value="$item->lease_end_date"
            required
        />

        <!-- Status -->
        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$item->is_active"
            required
        />
        <hr/>

        <!-- Save Button -->
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>

<!-- jQuery Handling -->
<script>
    function updateDropdown(url, targetId, placeholder = 'Select an option', selected = null) {
        const dropdown = $('#' + targetId);

        $.get(url)
            .done(function(response) {
                if (response.status_code === '000') {
                    dropdown.empty().append(`<option value="">${placeholder}</option>`);
                    response.data.forEach(item => {
                        const isSelected = selected && selected == item.id ? 'selected' : '';
                        dropdown.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
                    });
                } else {
                    console.warn(`Warning: ${response.message}`);
                }
            })
            .fail(function(xhr) {
                console.error("Failed to load dropdown:", xhr.responseText);
            })
            .always(function() {
                dropdown.selectpicker?.('refresh');
            });
    }

    $(document).ready(function () {
        const propertyId = $('#property_id').val();
        const selectedUnitId = "{{ $item->property_unit_id }}";
        const selectedRoomId = "{{ $item->room_id }}";

        // Load Property Units on page load if property_id exists
        if (propertyId) {
            updateDropdown(
                `/api/clients/common/property-units?filter_property=${propertyId}`,
                'property_unit_id',
                'Select Property Unit',
                selectedUnitId
            );
        }

        // On Property change, update Property Units
        $('#property_id').on('change', function () {
            const newPropertyId = $(this).val();
            $('#property_unit_id').empty();
            $('#room_id').empty();
            if (newPropertyId) {
                updateDropdown(
                    `/api/clients/common/property-units?filter_property=${newPropertyId}`,
                    'property_unit_id',
                    'Select Property Unit'
                );
            }
        });

        // Load Rooms on page load if property_unit_id exists
        if (selectedUnitId) {
            updateDropdown(
                `/api/clients/common/rooms?filter_property_unit=${selectedUnitId}`,
                'room_id',
                'Select Room',
                selectedRoomId
            );
        }

        // On Property Unit change, update Rooms
        $('#property_unit_id').on('change', function () {
            const unitId = $(this).val();
            $('#room_id').empty();
            if (unitId) {
                updateDropdown(
                    `/api/clients/common/rooms?filter_property_unit=${unitId}`,
                    'room_id',
                    'Select Room'
                );
            }
        });
    });
    function getUnitPrice(bookingPeriodId, propertyUnitId) {
        if (!bookingPeriodId || !propertyUnitId) return;

        const url = `/api/clients/common/getPrice?booking_period_id=${bookingPeriodId}&property_unit_id=${propertyUnitId}`;

        $.post(url)
            .done(function (response) {
                if (response.status_code === '000' && response.data) {
                    $('#sub_total').val(response.data);
                } else {
                    $('#sub_total').val('');
                    console.warn("Price not found or invalid response.");
                }
            })
            .fail(function (xhr) {
                $('#sub_total').val('');
                console.error("Error fetching price:", xhr.responseText);
            });
    }
    $(document).ready(function () {
        function fetchPriceIfReady() {
            const bookingPeriodId = $('#booking_period_id').val();
            const propertyUnitId = $('#property_unit_id').val();
            getUnitPrice(bookingPeriodId, propertyUnitId);
        }

        $('#booking_period_id, #property_unit_id').on('change', fetchPriceIfReady);

        // Optional: Fetch price on page load if both are already selected
        fetchPriceIfReady();
    });
</script>
