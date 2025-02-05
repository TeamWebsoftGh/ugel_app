<form method="POST" id="property_category" action="{{route('property-categories.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$property_category->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$property_category->name}}">
    <div class="row">
        <x-form.input-field
            name="name"
            label="Name"
            type="text"
            placeholder="Name"
            :value="$property_category->name"
            required
        />
        <x-form.input-field
            name="short_name"
            label="Short Name"
            type="text"
            placeholder="Short Name"
            :value="$property_category->short_name"
            required
        />

        <x-form.input-field
            name="is_active"
            label="Status"
            type="select"
            :options="['1' => 'Active', '0' => 'Inactive']"
            :value="$property_category->is_active"
            required
        />

        <x-form.input-field
            name="description"
            label="Description"
            type="textarea"
            placeholder="Enter a description"
            :value="$property_category->description"
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

