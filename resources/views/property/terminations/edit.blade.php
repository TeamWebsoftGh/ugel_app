<form method="POST" id="termination" action="{{route('property.employee-exits.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$termination->id}}">
    <input type="hidden" id="_name" name="me" value="{{$termination->employee->FullName}}">
    <input type="hidden" id="termination_type_hidden" name="termination_type_hidden" value="{{$termination->termination_type_id}}">
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label for="username" class="control-label">Employee</label>
            <select name="employee_id" @if($termination->employee_id != null)readonly @endif id="employee_id"
                    class="selectpicker form-control"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>trans('general.employee')])}}...'>
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->FullName}}</option>
                @endforeach
                @if(isset($termination->employee->id))
                    <option value="{{$termination->employee->id}}" selected>{{$termination->employee->FullName}}</option>
                @endif
            </select>
            <span class="input-note text-danger" id="error-terminated_employee"> </span>
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Termination Type')}}</label>
            <select name="termination_type_id" id="termination_type_id" class="form-control selectpicker"
                    data-live-search="true"
                    title='{{__('Termination Type')}}'>
                @foreach($termination_types as $termination_type)
                    <option value="{{$termination_type->id}}" @selected(old('termination_type_id', $termination->termination_type_id) == $termination_type->id)>{{$termination_type->termination_title}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-termination_type_id"> </span>
            @error('termination_type_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="name" class="control-label">Notice Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="notice_date" id="notice_date" class="form-control date"
                   value="{{old('notice_date', $termination->notice_date)}}">
            <span class="input-note text-danger" id="error-notice_date"> </span>
            @error('notice_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="termination_date" class="control-label">Termination Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="termination_date" id="termination_date" class="form-control date"
                   value="{{old('termination_date', $termination->termination_date)}}">
            <span class="input-note text-danger" id="error-termination_date"> </span>
            @error('termination_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $termination->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12">
            @include("shared.save-button")
            @if(isset($termination->employee->id))
                @can('view-details-employee')
                    <a href="{{route("employees.show", $termination->employee_id)}}" class="btn btn-success btn-sm"><i class="fa fa-chain"></i> View Details</a>
                @endcan
            @endif
        </div>
    </div>
</form>
