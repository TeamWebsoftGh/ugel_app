<form method="POST" id="booking_period" action="{{ route('court-hearings.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">

    <div class="row">
        <!-- Name -->
        <x-form.input-field
            name="court_case_id"
            label="Court Case"
            type="select"
            :options="$court_cases->pluck('display_name', 'id')"
            :value="$item->court_case_id"
            required
        />

        <x-form.input-field
            name="venue"
            label="Venue"
            type="text"
            :value="$item->venue"
            required
        />

        <x-form.input-field
            name="judge"
            label="Judge"
            type="text"
            :value="$item->judge"
            required
        />

        <!-- Booking End Date -->
        <x-form.input-field
            name="date"
            label="Date"
            type="date"
            :value="$item->date"
            required
        />
        <x-form.input-field
            name="time"
            label="Time"
            type="time"
            :value="$item->time"
        />
        <!-- Status -->
        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Closed']"
            :value="$item->is_active"
            required
        />
        <x-form.input-field
            name="attachments"
            label="Upload Attachments"
            type="multifile"
            :value="$item->attachments"
        />
        <x-form.input-field
            name="notes"
            label="Note"
            type="textarea"
            class="col-md-12"
            :value="$item->notes"
            required
        />
        <x-form.input-field
            name="outcome"
            label="Outcome"
            class="col-md-12"
            type="textarea"
            :value="$item->outcome"
            required
        />
        <!-- Save Button -->
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
