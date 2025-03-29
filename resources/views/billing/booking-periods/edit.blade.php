<form method="POST" id="booking_period" action="{{ route('booking-periods.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">

    <div class="row">
        <!-- Name -->
        <x-form.input-field
            name="name"
            label="Name"
            type="text"
            placeholder="Name"
            :value="$item->name"
            required
        />

        <!-- Type -->
        <x-form.input-field
            name="type"
            label="Type"
            type="select"
            id="type"
            :options="['vacation' => 'Vacation Stay', 'student' => 'Student Accommodation', 'other' => 'Other']"
            :value="$item->type"
            required
        />

        <!-- Booking Start Date -->
        <x-form.input-field
            name="booking_start_date"
            label="Booking Start Date"
            type="date"
            :value="$item->booking_start_date"
            required
        />

        <!-- Booking End Date -->
        <x-form.input-field
            name="booking_end_date"
            label="Booking End Date"
            type="date"
            :value="$item->booking_end_date"
            required
        />

        <!-- Extension Date -->
        <x-form.input-field
            name="extension_date"
            label="Extension Date"
            type="date"
            :value="$item->extension_date"
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

        <!-- Hostels & Prices -->
        <div class="form-group col-12">
            <h5>Assign Prices to Hostels</h5>
            @foreach($hostels as $hostel)
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <label>{{ $hostel->property->property_name }} - {{ $hostel->unit_name }}</label>
                        <input type="hidden" name="property_unit_ids[]" value="{{ $hostel->id }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number"
                               name="prices[]"
                               class="form-control price-input"
                               data-rent-amount="{{ $hostel->rent_amount }}"
                               data-general-amount="{{ $hostel->general_rent }}"
                               placeholder="0.00"
                               value={{$hostel->existing_price}}
                               step="0.01"
                               required>
                    </div>
                    <div class="col-md-3">
                        <select name="rent_types[]" class="form-control rent-type" required>
                            <option value="daily" {{ $hostel->existing_rent_type == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="monthly" {{ $hostel->existing_rent_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ $hostel->existing_rent_type == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="per-sem" {{ $hostel->existing_rent_type == 'per-sem' ? 'selected' : '' }}>Per-Sem</option>
                            <option value="custom" {{ $hostel->existing_rent_type == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>
                </div>
            @endforeach
        </div>

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
