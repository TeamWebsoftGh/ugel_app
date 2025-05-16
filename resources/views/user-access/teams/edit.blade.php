<form method="POST" id="team" action="{{ route('admin.teams.store') }}">
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
            name="user_type"
            label="Type"
            type="select"
            :options="['user' => 'User', 'customer' => 'Customer', 'other' => 'Other']"
            :value="$item->user_type"
            required
        />

        <x-form.input-field
            name="team_lead_id"
            label="Team Lead"
            type="select"
            :options="$users->pluck('fullname', 'id')"
            :value="$item->team_lead_id"
        />

        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$item->is_active"
            required
        />

        <x-form.input-field
            name="description"
            label="Description"
            type="textarea"
            placeholder="Enter a description"
            :value="$item->description"
        />

        <hr/>

        <!-- Hostels & Prices -->
        <div class="form-group col-12">
            <h5>Assign Users <span class="text-danger">*</span></h5>

            <div class="row">
                @foreach ($users as $user)
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="assigned_users[]"
                                   id="user_{{ $user->id }}"
                                   value="{{ $user->id }}"
                                {{ in_array($user->id, old('assigned_users', $item->users->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="user_{{ $user->id }}">
                                {{ $user->fullname }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
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
