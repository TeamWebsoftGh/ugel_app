<form method="POST" id="booking" action="{{ route('bookings.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">

    <div class="row">
        <!-- Type -->
        <x-form.input-field
            name="type"
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
            :options="$customers->pluck('name', 'id')"
            :value="$item->booking_period_id"
        />

        <x-form.input-field
            name="property_id"
            label="Property"
            type="select"
            :options="$customers->pluck('name', 'id')"
            :value="$item->property_id"
        />

        <x-form.input-field
            name="property_unit_id"
            label="Property Unit"
            type="select"
            :options="$customers->pluck('name', 'id')"
            :value="$item->property_unit_id"
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
    $(document).ready(function() {
        // Update price inputs and rent type selects on page load
        updateRentTypesAndPrices();

        // Event listener for when type changes
        $('#type').change(function() {
            updateRentTypesAndPrices();
        });

        function updateRentTypesAndPrices() {
            var type = $('#type').val();

            $('.rent-type').each(function() {
                if ($(this).find('option:selected').val() === '') { // Only update if no value is selected
                    if (type === 'vacation') {
                        $(this).html(`
                        <option value="daily" selected>Daily</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem">Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    }
                    else if (type === 'student') {
                        $(this).html(`
                        <option value="daily">Daily</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem" selected>Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    } else {
                        $(this).html(`
                        <option value="daily">Daily</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem">Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    }
                }
            });

            $('.price-input').each(function() {
                if (type === 'vacation') {
                    // Always update price for vacation type
                    $(this).val($(this).data('general-amount'));
                }
                else if (!$(this).val()) {
                    // Only update if empty for other types
                    $(this).val($(this).data('rent-amount'));
                }
            });
        }
    });
</script>
