<form method="POST" id="medical" action="{{route('property.payments.store')}}">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$medical->id}}">
    <input type="hidden" id="_name" name="me" value=" travel for {{$medical->FullName}}">
    <input type="hidden" id="travel_type_hidden" name="travel_type_hidden" value="{{$medical->travel_type_id}}">
    <div class="row">
        @include("shared.form.select-property-type", ["employee_id" => $medical->employee_id])
        <div class="form-group col-6 col-md-4">
            <label>{{__('Date of Examination')}} <span class="text-danger">*</span></label>
            <input type="date" name="exam_date" id="exam_date" required class="form-control"
                   value="{{old('exam_date', $medical->exam_date)}}">
            <span class="input-note text-danger" id="error-exam_date"> </span>
            @error('exam_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        @foreach ($fillableFields as $field)
            <div class="form-group col-6 col-md-4">
                <label>{{ ucfirst(str_replace('_', ' ', $field)) }} </label>
                <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control"
                       placeholder="{{ ucfirst(str_replace('_', ' ', $field)) }}"
                       value="{{ old($field, $medical->$field) }}">
                <span class="input-note text-danger" id="error-{{ $field }}"> </span>
                @error($field)
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
        @endforeach
        <div class="form-group col-6 col-md-4">
            <label>{{__('Blood Group')}}</label>
            <select name="travel_mode" id="travel_mode" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Blood Group')}}'>
                @foreach($blood_groups as $key => $medical_mode)
                    <option value="{{$key}}" @selected(old('blood_group', $medical->blood_group) == $key)>{{$medical_mode}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-blood_group"> </span>
            @error('blood_group')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Fit to Work?')}} <span class="text-danger">*</span></label>
            <select name="fit_to_work" id="fit_to_work" class="form-control selectpicker "
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>__('is fit?')])}}...'>
                <option value="1" @selected(old('fit_to_work', $medical->fit_to_work) == "1")>{{__('Yes')}}</option>
                <option value="0" @selected(old('fit_to_work', $medical->fit_to_work) == "0")>{{__('No')}}</option>
            </select>
            <span class="input-note text-danger" id="error-fit_to_work"> </span>
            @error('fit_to_work')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        @include("shared.form.attachment", ["attachment" => $medical->attachment])
        <div class="form-group col-12 col-md-12">
            <label for="general_assessment" class="control-label">General Assessment</label>
            <textarea class="form-control" id="general_assessment" name="general_assessment"
                      rows="3">{{old('general_assessment', $medical->general_assessment)}}</textarea>
            <span class="input-note text-danger" id="error-general_assessment"> </span>
            @error('general_assessment')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
<script>
    $('#blood_group').selectpicker('val', '{{$medical->blood_group}}');
    $('#employee').selectpicker('val', '{{$medical->employee_id}}');
</script>

