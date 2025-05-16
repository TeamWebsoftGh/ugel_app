<form method="POST" id="court_case" action="{{ route('court-cases.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->title }}">

    <div class="row">
        <!-- Name -->
        <x-form.input-field
            name="case_number"
            label="Case Number"
            type="text"
            :value="$item->case_number"
            required
        />
        <x-form.input-field
            name="title"
            label="Title"
            type="text"
            :value="$item->title"
            required
        />
        <x-form.input-field
            name="court_name"
            label="Court Name"
            type="text"
            :value="$item->court_name"
            required
        />

        <!-- Type -->
        <x-form.input-field
            name="type"
            label="Type"
            type="select"
            id="type"
            :options="['civil' => 'Civil', 'criminal' => 'Criminal', 'family' => 'Family', 'administrative' => 'Administrative']"
            :value="$item->type"
            required
        />

        <!-- Booking Start Date -->
        <x-form.input-field
            name="category"
            label="Category"
            type="select"
            :options="[
    'contract_dispute'     => 'Contract Dispute',
    'property_dispute'     => 'Property Dispute',
    'personal_injury'      => 'Personal Injury',
    'tort'                 => 'Tort Claim',
    'theft'                => 'Theft',
    'assault'              => 'Assault',
    'fraud'                => 'Fraud',
    'homicide'             => 'Homicide',
    'divorce'              => 'Divorce',
    'child_custody'        => 'Child Custody',
    'child_support'        => 'Child Support',
    'adoption'             => 'Adoption',
    'licensing'            => 'Licensing Dispute',
    'compliance'           => 'Regulatory Compliance',
    'immigration'          => 'Immigration',
    'tax'                  => 'Tax Appeal',
    'other'                => 'Other'
]"
            :value="$item->category"
            required
        />

        <!-- Booking End Date -->
        <x-form.input-field
            name="filed_at"
            label="Filed Date"
            type="date"
            :value="$item->filed_at"
            required
        />
        <x-form.input-field
            name="closed_at"
            label="Closed Date"
            type="date"
            :value="$item->closed_at"
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
        <x-form.input-field
            name="lawyer_id"
            label="Lawyer"
            type="select"
            :options="$users->pluck('fullname', 'id')"
            :value="$item->lawyer_id"
        />
        <x-form.input-field
            name="note"
            label="Note"
            type="textarea"
            class="col-md-12"
            :value="$item->note"
            required
        />
        <x-form.input-field
            name="description"
            label="Description"
            class="col-md-12"
            type="textarea"
            :value="$item->description"
            required
        />

        <x-form.input-field
            name="attachments"
            label="Upload Attachments"
            type="multifile"
            :value="$item->attachments"
        />
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
