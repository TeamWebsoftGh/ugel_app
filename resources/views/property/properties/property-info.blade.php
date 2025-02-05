<form method="POST" id="property" action="{{ route('properties.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $property->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $property->name }}">

    <div class="row clearfix">
        <!-- Property Name Input -->
        <x-form.input-field
            name="property_name"
            label="Property Name"
            type="text"
            placeholder="Enter Property Name"
            :value="$property->property_name"
            required
        />


        <!-- Property Code Input -->
        <x-form.input-field
            name="property_code"
            label="Property Code"
            type="text"
            placeholder="Enter Property Code"
            :value="$property->property_code"
            required
        />

        <!-- Property Purpose Select -->
        <x-form.input-field
            name="property_purpose_id"
            label="Purpose"
            type="select"
            :options="$property_purposes->pluck('name', 'id')"
            :value="$property->property_purpose_id"
            required
        />

        <!-- Property Category Select -->
        <x-form.input-field
            name="property_category_id"
            label="Property Category"
            type="select"
            :options="$property_categories->pluck('name', 'id')"
            :value="$property->property_category_id"
            required
        />

        <!-- Property Type Select -->
        <x-form.input-field
            name="property_type_id"
            label="Property Type"
            type="select"
            :options="$property_types->pluck('name', 'id')"
            :value="$property->property_type_id"
            required
        />

        <!-- Status Select -->
        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$property->is_active"
            required
        />

        <!-- Complaint Date Input -->
        <x-form.input-field
            name="complaint_date"
            label="Complaint Date"
            type="date"
            :value="$property->complaint_date"
            required
        />

        <!-- Description Textarea -->
        <x-form.input-field
            name="description"
            label="Description"
            type="textarea"
            placeholder="Enter a description"
            :value="$property->description"
        />

        <!-- Save Button -->
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
