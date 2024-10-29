<form method="POST" action="{{route('property.offenses.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$offense->id}}">
    <input type="hidden" id="_name" name="me" value=" sanction for {{$offense->employee->FullName}}">
    <input type="hidden" id="warning_type_hidden" name="warning_type_hidden" value="{{$offense->warning_type_id}}">
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label for="username" class="control-label">Employee</label>
            <select name="employee_id" @if($offense->employee_id != null)readonly @endif id="employee_id"
                    class="selectpicker form-control"
                    data-live-search="true" title='{{__('Selecting',['key'=>trans('general.employee')])}}...'>
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->FullName}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-employee"> </span>
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Offense Type')}}</label>
            <select name="offense_type_id" id="offense_type_id" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Offense Type')}}'>
                @foreach($offense_types as $offense_type)
                    <option value="{{$offense_type->id}}">{{$offense_type->name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-offense_type_id"> </span>
            @error('offense_type_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="offence_date" class="control-label">Offense Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="offence_date" id="offence_date" class="form-control date"
                   value="{{old('offence_date', $offense->offence_date)}}">
            <span class="input-note text-danger" id="error-offence_date"> </span>
            @error('offence_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Is Investigation Required?')}}</label>
            <select name="investigation_required" id="investigation_required" class="form-control selectpicker "
                    data-live-search="true">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <span class="input-note text-danger" id="error-investigation_required"> </span>
            @error('investigation_required')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $offense->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <hr/>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Warning Type')}}</label>
            <select name="warning_type_id" id="warning_type_id" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Warning Type')}}'>
                @foreach($warning_types as $warning_type)
                    <option value="{{$warning_type->id}}">{{$warning_type->name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-warning_type_id"> </span>
            @error('warning_type_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="warning_date" class="control-label">Warning Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="warning_date" id="warning_date" class="form-control date"
                   value="{{old('warning_date', $offense->warning_date)}}">
            <span class="input-note text-danger" id="error-notice_date"> </span>
            @error('warning_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Query Given?')}}</label>
            <select name="query_given" id="query_given" class="form-control selectpicker "
                    data-live-search="true">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <span class="input-note text-danger" id="error-query_given"> </span>
            @error('query_given')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Query Responded?')}}</label>
            <select name="query_responded" id="query_responded" class="form-control selectpicker "
                    data-live-search="true">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <span class="input-note text-danger" id="error-query_responded"> </span>
            @error('query_responded')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>

        <div class="form-group col-12 col-md-12">
            <label for="investigation_report" class="control-label">Investigation Report</label>
            <textarea class="form-control" id="investigation_report" name="investigation_report"
                      rows="3">{{old('investigation_report', $offense->investigation_report)}}</textarea>
            <span class="input-note text-danger" id="error-investigation_report"> </span>
            @error('investigation_report')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
