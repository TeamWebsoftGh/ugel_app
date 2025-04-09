<form method="POST" id="resource" action="{{route('resource.resources.store')}}" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$resource->id}}">
    <input type="hidden" id="_name" name="me" value="{{$resource->title}}">
    <input type="hidden" id="_file" name="old_file" value="{{$resource->file_path}}">
    <div class="row">
        <x-form.input-field
            name="title"
            label="Title"
            type="text"
            class="col-md-12"
            :value="$resource->title"
            required
        />
        <!-- Type -->
        <x-form.input-field
            name="category_id"
            label="Category"
            type="select"
            :options="$categories->pluck('name', 'id')"
            :value="$resource->category_id"
            required
        />
        <x-form.input-field
            name="target_group"
            label="Target"
            type="select"
            id="target_group"
            :options="['staff' => 'Staff', 'customer' => 'Customer', 'property' => 'Property']"
            :value="$resource->target_group"
            required
        />

        <x-form.input-field
            name="property_type_id"
            label="Property Type"
            type="select"
            class="field-property col-md-4"
            :options="$property_types->pluck('name', 'id')"
            :value="$resource->property_type_id"
        />

        <x-form.input-field
            name="property_id"
            label="Property"
            type="select"
            class="field-property col-md-4"
            :options="[]"
            :value="$resource->property_id"
        />

        <x-form.input-field
            name="client_type_id"
            label="Client Type"
            type="select"
            class="field-property col-md-4"
            :options="$client_types->pluck('name', 'id')"
            :value="$resource->client_type_id"
        />

        <x-form.input-field
            name="team_id"
            label="Team"
            type="select"
            class="field-team col-md-4"
            :options="$teams->pluck('name', 'id')"
            :value="$resource->team_id"
        />

        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$resource->is_active"
            required
        />
        <x-form.input-field
            name="file"
            label="Upload Document"
            type="file"
            :value="$resource->file_path"
        />
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>

<script>
    function toggleTargetFields() {
        var target = $('#target_group').val();

        if (target === 'staff') {
            $('.field-property').closest('.col-md-12, .col-md-6, .form-group').hide();
            $('.field-team').closest('.col-md-12, .col-md-6, .form-group').show();
        } else {
            $('.field-property').closest('.col-md-12, .col-md-6, .form-group').show();
            $('.field-team').closest('.col-md-12, .col-md-6, .form-group').hide();
        }
    }

    $(document).ready(function() {
        toggleTargetFields(); // On page load
        $('#target_group').on('change', function () {
            toggleTargetFields();
        });
    });
</script>
