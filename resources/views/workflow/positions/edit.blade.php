<form method="POST" id="position" action="{{route('workflows.positions.store')}}"  class="form-horizontal">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$workflowPosition->id}}">
    <input type="hidden" id="_name" name="me" value="{{$workflowPosition->position_name}}">
    <div class="row">
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Name')}} *</label>
            <input type="text" name="position_name" id="position_name" value="{{$workflowPosition->position_name}}"
                   class="form-control" placeholder="Eg. Head of IT">
            <span class="input-note text-danger" id="error-position_name"> </span>
            @error('position_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Position Type')}} *</label>
            <select name="workflow_position_type" id="workflow_position_type" class="form-control selectpicker"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('Position Type')])}}...'>
                <option value="" disabled selected>{{__('Select Position Type')}}</option>
                @forelse($positionTypes as $positionType)
                    <option value="{{$positionType->code}}">{{$positionType->name}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-workflow_position_type"> </span>
            @error('workflow_position_type')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group" id="cat_container">
            <label>{{__('Category')}} </label>
            <select name="category" id="category" class="form-control selectpicker"
                    data-live-search="true" title='{{__('Selecting',['key'=>__('Category')])}}...'>
            </select>
            <span class="input-note text-danger" id="error-category"> </span>
            @error('category')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('User')}} *</label>
            <select name="user_id" id="employee_id" data-ignore="1" class="form-control selectpicker"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('User')])}}...'>
                <option value="" disabled selected>{{__('Select one...')}}</option>
                @forelse($users as $employee)
                    <option value="{{$employee->id}}">{{$employee->fullname}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-user_id"> </span>
            @error('user_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="col-md-4 col-6 form-group">
            <label>{{__('Reports to')}} </label>
            <select name="reports_to" id="reports_to" data-ignore="1" class="form-control selectpicker"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('Reports to')])}}...'>
                <option value="" disabled selected>{{__('Select one...')}}</option>
                @forelse($users as $employee)
                    <option value="{{$employee->id}}">{{$employee->fullname}}</option>
                @empty
                @endforelse
            </select>
            <span class="input-note text-danger" id="error-reports_to"> </span>
            @error('reports_to')
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
            <textarea class="form-control" rows="2" name="description">{{old("remarks", $workflowPosition->description)}}</textarea>
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
        $('#user_id').selectpicker('val', '{{$workflowPosition->user_id}}');
        $('#status').selectpicker('val', '{{$workflowPosition->is_active}}');
        $('#reports_to').selectpicker('val', '{{$workflowPosition->reports_to}}');
        $('#category').selectpicker('val', '{{$workflowPosition->subject_id}}');
        $('#workflow_position_type').selectpicker('val', '{{$workflowPosition->workflowPositionType->code}}');

        getCategory($('#workflow_position_type').val())
        $('#workflow_position_type').change(function() {
            getCategory($(this).val())
        });
    });
    function getCategory(type){
        let _token = $('input[name="_token"]').val();
        if (type == 'hod') {
            $('#cat_container').show();
            $.ajax({
                url:"{{ route('dynamic_department') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(result)
                {
                    $('#category').selectpicker("destroy");
                    $('#category').html(result);
                    $('#category').selectpicker();
                    $('#category').selectpicker('val', '{{$workflowPosition->subject_id}}');
                }
            });
        }else if(type == 'team'){
            $('#cat_container').show();
            $.ajax({
                url:"{{ route('dynamic_team') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(result)
                {
                    $('#category').selectpicker("destroy");
                    $('#category').html(result);
                    $('#category').selectpicker();
                    $('#category').selectpicker('val', '{{$workflowPosition->subject_id}}');
                }
            });
        } else if(type == 'general-manager'){
            $('#cat_container').show();
            $.ajax({
                url:"{{ route('dynamic_subsidiary') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(result)
                {
                    $('#category').selectpicker("destroy");
                    $('#category').html(result);
                    $('#category').selectpicker();
                    $('#category').selectpicker('val', '{{$workflowPosition->subject_id}}');
                }
            });
        }else if(type == 'unit-head'){
            $('#cat_container').show();
            $.ajax({
                url:"{{ route('dynamic_unit') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(result)
                {
                    $('#category').selectpicker("destroy");
                    $('#category').html(result);
                    $('#category').selectpicker();
                    $('#category').selectpicker('val', '{{$workflowPosition->subject_id}}');
                }
            });
        }else{
            $('#category').selectpicker("destroy");
            $('#category').html('');
            $('#category').selectpicker();
            $('#cat_container').hide();
        }
    }
</script>
