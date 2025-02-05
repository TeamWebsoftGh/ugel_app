<div class="row">
    <div class="col-md-12 form-group">
        <label>{{__('Employee Category')}} </label>
        <select name="employee_category" id="employee_category" class="form-control selectpicker"
                data-live-search="true"
                title='{{__('Selecting',['key'=>__('Employee Category')])}}...'>
            <option value="" selected>{{__('Select Employee Category...')}}</option>
            @foreach($emp_categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
        </select>
        <span class="input-note text-danger" id="error-employee_category"> </span>
        @error('employee_category')
        <span class="input-note text-danger">{{ $message }} </span>
        @enderror
    </div>
    <div class="col-md-12 form-group">
        <label>{{__('Employee Type')}} </label>
        <select name="employee_type" id="employee_type" class="form-control selectpicker"
                data-live-search="true"
                title='{{__('Selecting',['key'=>__('Employee Type')])}}...'>
            <option value="" selected>{{__('Select Type...')}}</option>
            @foreach($emp_types as $type)
                <option value="{{$type->id}}">{{$type->emp_type_name}}</option>
            @endforeach
        </select>
        <span class="input-note text-danger" id="error-employee_status"> </span>
        @error('employee_type')
        <span class="input-note text-danger">{{ $message }} </span>
        @enderror
    </div>
    <div class="col-md-12 form-group">
        <label>{{__('Location')}} </label>
        <select name="location" id="employee_location" class="form-control selectpicker"
                data-live-search="true"
                title='{{__('Selecting',['key'=>__('Location')])}}...'>
            <option value="" selected>{{__('Select Location...')}}</option>
            @foreach($locations as $location)
                <option value="{{$location->id}}">{{$location->location_name}}</option>
            @endforeach
        </select>
        <span class="input-note text-danger" id="error-location"> </span>
        @error('location')
        <span class="input-note text-danger">{{ $message }} </span>
        @enderror
    </div>
    <div class="col-md-12 form-group">
        <label>{{__('Department')}} </label>
        <select name="department" id="employee_department" class="form-control selectpicker designation"
                data-live-search="true" data-live-search-style="begins"
                data-designation_name="designation_name"
                title='{{__('Selecting',['key'=>__('Department')])}}...'>
            <option value="" selected>{{__('Select Department...')}}</option>
            @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->department_name}}</option>
            @endforeach
        </select>
        <span class="input-note text-danger" id="error-department"> </span>
        @error('department')
        <span class="input-note text-danger">{{ $message }} </span>
        @enderror
    </div>
</div>
