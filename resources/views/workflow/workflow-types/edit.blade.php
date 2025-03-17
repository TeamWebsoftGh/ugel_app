<form method="POST" id="workflow_type" action="{{ route('workflows.workflow-types.store') }}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" name="id" value="{{ $workflowType->id }}">
    <input type="hidden" name="me" value="{{ $workflowType->name }}">

    <div class="row">
        <x-form.input-field name="name" label="Name" type="text"
                            placeholder="Eg. Employee Bank Details" :value="$workflowType->name" required />

        <x-form.input-field name="code" label="Code" type="text"
                            placeholder="Eg. bank-details" :value="$workflowType->code" required />

        <x-form.input-field name="subject_type" label="Subject Type" type="select"
                            :options="$models" :value="$workflowType->subject_type" required />

        <x-form.input-field name="sort_order" label="Sort Order" type="number"
                            min="0" :value="$workflowType->sort_order" required />

        <x-form.input-field name="is_active" label="Status" type="select"
                            :options="['1' => 'Active', '0' => 'Inactive']"
                            :value="$workflowType->is_active" required />

        <x-form.input-field name="description" label="Description" type="textarea"
                            placeholder="Enter Description" :value="$workflowType->description" />

        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
