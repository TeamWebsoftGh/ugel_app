<form method="POST" action="{{route('workflows.workflow-types.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflowType->id}}">
    <input type="hidden" id="_name" name="me" value=" {{$workflowType->name}}">
    <div class="row">
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Name')}} *</label>
            <input type="text" name="name" id="name" value="{{$workflowType->name}}"
                   class="form-control" placeholder="Eg. Employee Bank Details">
            <span class="input-note text-danger" id="error-name"> </span>
            @error('name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Position Code')}} *</label>
            <input type="text" name="position_code" id="position_code" value="{{$workflowType->code}}"
                   class="form-control" placeholder="Eg. hod">
            <span class="input-note text-danger" id="error-position_code"> </span>
            @error('position_code')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Subject Type')}} *</label>
            <input type="text" name="subject_type" id="subject_type" value="{{$workflowType->subject_type}}"
                   class="form-control" placeholder="App\Models\Department">
            <span class="input-note text-danger" id="error-subject_type"> </span>
            @error('subject_type')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Sort order')}} *</label>
            <input type="number" min="0" name="sort_order" id="sort_order" value="{{$workflowType->sort_order}}"
                   class="form-control">
            <span class="input-note text-danger" id="error-sort_order"> </span>
            @error('sort_order')
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
            <textarea class="form-control" rows="2" name="description">{{old("remarks", $workflowType->description)}}</textarea>
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
        let date = $('.date');
        date.datepicker({
            format: '{{ env('Date_Format_JS')}}',
            autoclose: true,
            todayHighlight: true
        });
    });
    $('#status').selectpicker('val', '{{$workflowType->is_active}}');

</script>
