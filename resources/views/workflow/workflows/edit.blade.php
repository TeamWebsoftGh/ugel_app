<form method="POST" action="{{route('workflows.workflows.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflow->id}}">
    <input type="hidden" id="_name" name="me" value="{{$workflow->workflow_name}}">
    <div class="row">
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Workflow Type')}} <span class="text-danger">*</span></label>
            <select name="workflow_type" id="workflow_type" class="form-control selectpicker"
                    data-live-search="true" title='{{__('Selecting',['key'=>__('Workflow Type')])}}...'>
                @forelse($workflowTypes as $workflowType)
                    <option value="{{$workflowType->id}}">{{$workflowType->name}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-workflow_type"> </span>
            @error('workflow_type')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Workflow Name')}} <span class="text-danger">*</span></label>
            <input type="text" name="workflow_name" id="workflow_name" value="{{$workflow->workflow_name}}"
                   class="form-control" placeholder="Eg. First Leave Approver">
            <span class="input-note text-danger" id="error-workflow_name"> </span>
            @error('workflow_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Position Type(Approver)')}} <span class="text-danger">*</span></label>
            <select name="workflow_position_type" id="workflow_position_type" class="form-control selectpicker"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('Position Type')])}}...'>
                <option value="" disabled selected>{{__('Select Position Type')}}</option>
                @forelse($positionTypes as $positionType)
                    <option value="{{$positionType->id}}">{{$positionType->name}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-workflow_position_type"> </span>
            @error('workflow_position_type')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Action')}} <span class="text-danger">*</span></label>
            <select name="action" id="action" class="form-control selectpicker"
                    data-live-search="true" data-live-search-style="begins"
                    title='{{__('Selecting',['key'=>__('')])}}...'>
                <option value="approve">{{__('Approve')}}</option>
                <option value="inform">{{__('Inform')}}</option>
            </select>
            <span class="input-note text-danger" id="error-action"> </span>
            @error('action')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Flow Sequence')}} <span class="text-danger">*</span></label>
            <select name="flow_sequence" id="flow_sequence_k" class="form-control selectpicker"
                    data-live-search="true" title='{{__('Selecting',['key'=>__('flow sequence')])}}...'>
                @for ($i = 1; $i <= 10; $i++)
                    <option value={{$i}}>Sequence {{$i}}</option>
                @endfor
            </select>
            <span class="input-note text-danger" id="error-flow_sequence"> </span>
            @error('flow_sequence')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Status')}} *</label>
            <select name="status" id="status" class="form-control selectpicker" data-ignore="1"
                    data-live-search="true" data-live-search-style="begins"
                    title='{{__('Selecting',['key'=>__('')])}}...'>
                <option value="" disabled selected>{{__('Select Status...')}}</option>
                <option value="1">{{__('Active')}}</option>
                <option value="0">{{__('Inactive')}}</option>
            </select>
            <span class="input-note text-danger" id="error-status"> </span>
            @error('status')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-sm-12 col-xl-8">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" rows="2" name="description">{{old("remarks", $workflow->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <div class="form-group col-12">
            @include("shared.new-controls")
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#action').selectpicker('val', '{{$workflow->action}}');
        $('#status').selectpicker('val', '{{$workflow->is_active}}');
        $('#workflow_type').selectpicker('val', '{{$workflow->workflow_type_id}}');
        $('#flow_sequence_k').selectpicker('val', {{$workflow->flow_sequence}});
        $('#workflow_position_type').selectpicker('val', '{{$workflow->workflow_position_type_id}}');
    });
</script>
