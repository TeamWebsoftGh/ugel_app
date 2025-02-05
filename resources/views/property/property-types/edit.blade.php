<form method="POST" id="property_type" action="{{route('property-types.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$property_type->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$property_type->name}}">
    <div class="row">
        <x-form.input-field
            name="name"
            label="Name"
            type="text"
            placeholder="Name"
            :value="$property_type->name"
            required
        />
        <x-form.input-field
            name="short_name"
            label="Short Name"
            type="text"
            placeholder="Short Name"
            :value="$property_type->short_name"
            required
        />
        <!-- Property Category Select -->
        <x-form.input-field
            name="property_category_id"
            label="Property Category"
            type="select"
            :options="$property_categories->pluck('name', 'id')"
            :value="$property_type->property_category_id"
            required
        />
        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$property_type->is_active"
            required
        />

        <x-form.input-field
            name="description"
            label="Description"
            type="textarea"
            placeholder="Enter a description"
            :value="$property_type->description"
            class="col-md-8"
        />
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        let date = $('.date');
        date.datepicker({
            format: '{{ env('Date_Format_JS')}}',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

