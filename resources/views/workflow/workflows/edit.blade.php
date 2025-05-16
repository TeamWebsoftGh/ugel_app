<form method="POST" id="workflow" action="{{route('workflows.workflows.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflow->id}}">
    <input type="hidden" id="_name" name="me" value="{{$workflow->workflow_name}}">
    <div class="row">
        <x-form.input-field
            name="workflow_type"
            label="Workflow Type"
            type="select"
            :options="$workflowTypes->pluck('name', 'id')"
            :value="$workflow->workflow_type_id"
            required
        />
        <x-form.input-field
            name="workflow_name"
            label="Workflow Name"
            type="text"
            :value="$workflow->workflow_name"
            required
        />
        <x-form.input-field
            name="workflow_position_type"
            label="Position Type(Approver)"
            type="select"
            :options="$positionTypes->pluck('name', 'id')"
            :value="$workflow->workflow_position_type_id"
            required
        />
        <x-form.input-field name="action_type" label="Action Type" type="select" :options="['approve' => 'Approve', 'inform' => 'Inform']" :value="$workflow->action_type" required />
        <x-form.input-field name="flow_sequence" label="Flow Sequence" type="select" :options="$flowSequenceOptions" :value="$workflow->flow_sequence" required />

        <x-form.input-field
            name="return_to"
            label="Return to"
            type="select"
            :options="[]"
            :value="$workflow->return_to"
        />
        <x-form.input-field name="send_email" label="Send Email" type="select" :options="['1' => 'Yes', '0' => 'No']" :value="$workflow->send_email" required />
        <x-form.input-field name="is_active" label="Status" type="select" :options="['1' => 'Active', '0' => 'Inactive']" :value="$workflow->is_active" required />
        <x-form.input-field name="description" class="col-md-12" rows="3" label="Description" type="textarea" placeholder="Enter a description" :value="$workflow->description" />

    </div>
    <div class="form-group col-12">
        @include("shared.save-button")
    </div>
</form>
<script>
    $(document).ready(function () {
        const selectedWorkflowType = $('#workflow_type').val();
        $('#action').selectpicker('val', '{{$workflow->action}}');
        $('#flow_sequence_k').selectpicker('val', '{{$workflow->flow_sequence}}');

        // Initial load
        if (selectedWorkflowType) {
            loadReturnToOptions(selectedWorkflowType);
        }

        // On change
        $('#workflow_type').on('change', function () {
            loadReturnToOptions($(this).val());
        });

        function loadReturnToOptions(workflowTypeId) {
            if (!workflowTypeId) return;

            $.get(`/ajax/workflow/return-to-options/${workflowTypeId}`, function (data) {
                let options = `<option value="" disabled selected>Select...</option>`;
                $.each(data, function (id, name) {
                    options += `<option value="${id}">${name}</option>`;
                });
                $('#return_to').html(options).selectpicker?.('refresh');
            });
        }
    });

</script>
