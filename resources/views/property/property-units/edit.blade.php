<form method="POST" id="property_unit" action="{{ route('property-units.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $property_unit->id }}">
    <input type="hidden" id="_name" name="me" value="award for {{ $property_unit->name }}">

    <div class="row">
        <x-form.input-field name="property_id" label="Property" type="select"
                            :options="$properties->pluck('property_name', 'id')" :value="$property_unit->property_id" required />

        <x-form.input-field name="unit_name" label="Unit Name" type="text"
                            placeholder="Enter Unit Name" :value="$property_unit->unit_name" required />

        <x-form.input-field name="total_bedroom" label="Bedroom" type="number"
                            placeholder="Enter number of bedrooms" :value="$property_unit->total_bedroom" required />

        <x-form.input-field name="total_kitchen" label="Kitchen" type="number"
                            placeholder="Enter number of kitchens" :value="$property_unit->total_kitchen" required />

        <x-form.input-field name="total_bathroom" label="Bathroom" type="number"
                            placeholder="Enter number of bathrooms" :value="$property_unit->total_bathroom" required />

        <x-form.input-field name="rent_type" label="Rent Type" type="select"
                            :options="['monthly' => 'Monthly', 'yearly' => 'Yearly', 'semester' => 'Per Semester']"
                            :value="$property_unit->rent_type" required />

        <x-form.input-field name="rent_amount" label="Rent Amount" type="number"
                            placeholder="Enter unit rent" :value="$property_unit->rent_amount" required />

        <x-form.input-field name="rent_duration" label="Max Rent Duration" type="number"
                            placeholder="Enter day of month between 1 to 30" min="1" max="30"
                            :value="$property_unit->rent_duration" required />

        <x-form.input-field name="general_rent" label="Daily Rent Amount(Vacation Stay)" type="number"
                             :value="$property_unit->general_rent" />

        <x-form.input-field name="deposit_type" label="Deposit Type" type="select"
                            :options="['fixed' => 'Fixed', 'percentage' => 'Percentage']"
                            :value="$property_unit->deposit_type" required />

        <x-form.input-field name="security_deposit" label="Security Deposit" type="number"
                            placeholder="Enter deposit amount" :value="$property_unit->security_deposit" required />

        <x-form.input-field name="late_fee_type" label="Late Fee Type" type="select"
                            :options="['fixed' => 'Fixed', 'percentage' => 'Percentage']"
                            :value="$property_unit->late_fee_type" required />

        <x-form.input-field name="late_fee" label="Late Fee" type="number"
                            placeholder="Enter late fee" :value="$property_unit->late_fee" required />

        <x-form.input-field
            name="amenity_id"
            label="Amenities"
            id="amenity_id"
            type="multiselect"
            :options="$amenities->pluck('name', 'id')"
            multiple
            :value="$property_unit->amenities()->pluck('id')->toArray()"
        />

        <x-form.input-field name="description" class="col-md-12" label="Notes" type="textarea"
                            placeholder="Enter additional notes" :value="$property_unit->description" />


        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
