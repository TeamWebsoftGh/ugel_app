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

        <x-form.input-field
            name="rent_type"
            label="Rent Type"
            type="text"
            readonly
            :value="ucfirst($item->rent_type)"
        />

        <x-form.input-field
            name="rent_duration"
            label="Rent Duration"
            type="number"
            :value="$item->rent_duration"
        />
        <x-form.input-field
            name="booking_date"
            label="Booking Date"
            type="date"
            :value="$item->booking_date"
            required
        />

        <!-- Lease Start Date -->
        <x-form.input-field
            name="sub_total"
            label="Rate"
            type="text"
            readonly
            :value="$item->sub_total"
        />

        <!-- Total Amount -->
        <x-form.input-field
            name="total_price"
            label="Total Payable"
            type="text"
            readonly
            :value="$item->total_price"
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
        <h4>Total Payable: <span id="totalAmount">{{$item->total_price}}</span></h4>

        <!-- Save Button -->
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>

<!-- jQuery Handling -->
<script>
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
        if (!propertyUnitId) return;

        const query = bookingPeriodId
            ? `?booking_period_id=${bookingPeriodId}&property_unit_id=${propertyUnitId}`
            : `?property_unit_id=${propertyUnitId}`;

        const url = `/api/clients/common/getPrice${query}`;

        $.post(url)
            .done(function (response) {
                if (response.status_code === '000' && response.data) {
                    const price = parseFloat(response.data.price ?? 0);
                    const rentType = capitalize(response.data.rent_type);
                    const rentDurationRaw = response.data.rent_duration ?? '';

                    $('#rent_type').val(rentType);
                    $('#rent_duration').val(rentDurationRaw);
                    $('#sub_total').val(price.toFixed(2));

                    // Extract numeric value from rent_duration
                    const duration = parseInt(rentDurationRaw); // works if rentDurationRaw = "4 months"

                    const totalAmount = isNaN(duration) ? 0 : (price * duration);
                    const totalFormatted = totalAmount.toFixed(2);

                    // Display total
                    $('#total_price').val(totalFormatted); // for input
                    $('#totalAmount').text(totalFormatted); // for span
                } else {
                    $('#rent_type, #rent_duration, #sub_total, #total_price').val('');
                    $('#totalAmount').text('0.00');
                    console.warn("Price not found or invalid response.");
                }
            })
            .fail(function (xhr) {
                $('#rent_type, #rent_duration, #sub_total, #total_price').val('');
                $('#totalAmount').text('0.00');
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

        // Add live calculation on change
        $('#rent_duration, #sub_total').on('input', calculateTotalAmount);
    });

    function calculateTotalAmount() {
        const rentDuration = parseFloat($('#rent_duration').val()) || 0;
        const subTotal = parseFloat($('#sub_total').val()) || 0;

        const total = (rentDuration * subTotal).toFixed(2);
        $('#total_price').val(total);      // update input
        $('#totalAmount').text(total);     // update display span
    }

</script>
