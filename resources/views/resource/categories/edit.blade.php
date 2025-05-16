<form method="POST" id="category" action="{{route('resource.categories.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$category->id}}">
    <input type="hidden" id="_name" name="me" value="{{$category->name}}">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="row">
                <x-form.input-field
                    name="name"
                    label="Name"
                    type="text"
                    placeholder="Name"
                    :value="$category->name"
                    required
                />
                <!-- Status -->
                <x-form.input-field
                    name="is_active"
                    label="Status"
                    type="select"
                    :options="['1' => 'Active', '0' => 'Inactive']"
                    :value="$category->is_active"
                    required
                />
                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $('#status').selectpicker('val', '{{$category->is_active}}');
</script>
