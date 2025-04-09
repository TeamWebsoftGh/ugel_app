<form method="POST" id="position_types" action="{{route('workflows.position-types.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflowPositionType->id}}">
    <input type="hidden" id="_name" name="me" value="{{$workflowPositionType->name}}">
    <div class="row">
        <x-form.input-field name="name" label="Name" type="text" placeholder="Eg. Head of HR" :value="$workflowPositionType->name" required />
        <x-form.input-field name="position_code" label="Position Code" type="text" placeholder="Eg. hr" :value="$workflowPositionType->code" required />
        <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$workflowPositionType->is_active" required />
        <x-form.input-field name="description" class="col-md-12" rows="3" label="Description" type="textarea" placeholder="Enter a description" :value="$workflowPositionType->description" />
    </div>
    <div class="form-group">
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#is_active').selectpicker('val', '{{$workflowPositionType->is_active}}');
    });
</script>
