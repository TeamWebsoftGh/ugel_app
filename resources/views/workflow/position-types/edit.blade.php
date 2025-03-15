<form method="POST" id="position_types" action="{{route('workflows.position-types.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflowPositionType->id}}">
    <input type="hidden" id="_name" name="me" value="{{$workflowPositionType->deduction_name}}">
    <div class="row">
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Name')}} *</label>
            <input type="text" name="name" id="name" value="{{$workflowPositionType->name}}"
                   class="form-control" placeholder="Eg. Head of Department">
            <span class="input-note text-danger" id="error-name"> </span>
            @error('name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Position Code')}} *</label>
            <input type="text" name="position_code" id="position_code" value="{{$workflowPositionType->code}}"
                   class="form-control" placeholder="Eg. hod">
            <span class="input-note text-danger" id="error-position_code"> </span>
            @error('position_code')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Status')}} *</label>
            <select name="is_active" id="is_active" class="selectpicker"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('')])}}...'>
                <option value="1">{{__('Active')}}</option>
                <option value="0">{{__('Inactive')}}</option>
            </select>
            <span class="input-note text-danger" id="error-is_active"> </span>
            @error('is_active')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-sm-12 col-xl-8">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" rows="2" name="description">{{old("remarks", $workflowPositionType->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
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
