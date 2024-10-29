<form method="POST" id="property_type" action="{{route('property.designation-changes.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$designation_change->id}}">
    <input type="hidden" id="_name" name="me" value=" travel for {{$designation_change->employee->FullName}}">
    <div class="row">
        @include("shared.form.select-employee", ['employee_id' => $designation_change->employee_id])
        <div class="form-group col-6 col-md-4">
            <label>{{__('Staff ID')}} *</label>
            <input type="text" id="employee_staff_id" name="employee_staff_id" class="form-control"
                   value="{{old('employee_staff_id', $designation_change->employee->staff_id)}}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label for="old_designation" class="control-label">Old Designation <span class="text-danger">*</span></label>
            <select name="old_designation" readonly id="old_designation"
                    class="selectpicker form-control"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>trans('file.Designation')])}}...'>
                @foreach($designations as $designation)
                    <option value="{{$designation->id}}">{{$designation->designation_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-complaint_from"> </span>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="new_designation" class="control-label">New Designation <span class="text-danger">*</span></label>
            <select name="new_designation" id="new_designation"
                    class="selectpicker form-control"
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>trans('file.Designation')])}}...'>
                @foreach($designations as $designation)
                    <option value="{{$designation->id}}">{{$designation->designation_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-new_designation"> </span>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="change_date" class="control-label">Change Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="change_date" id="promotion_date" class="form-control date"
                   value="{{old('change_date', $designation_change->change_date)}}">
            <span class="input-note text-danger" id="error-change_date"> </span>
            @error('change_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-8">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $designation_change->description)}}</textarea>
            <span class="input-note text-danger" id="error-description"> </span>
            @error('description')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>
<script>

    $('#employee').selectpicker('val', '{{$designation_change->employee_id}}');
    $('#old_designation').selectpicker('val', '{{$designation_change->old_designation_id}}');
    $('#new_designation').selectpicker('val', '{{$designation_change->new_designation_id}}');

    $(document).ready(function () {
        $('#employee').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_employee_details') }}",
                    method: "POST",
                    data: {value: value, _token: _token},
                    success: function (result) {
                        console.log(result['department_id'])
                        console.log(result['location_id'])
                        $('#old_designation').selectpicker('val', result['designation_id']+'');
                        $('#new_designation').selectpicker('refresh');
                        $('#old_designation').prop('readonly', 'true');
                        $('#employee_staff_id').val(result['staff_id']);
                    }
                });
            }
        });
    });
</script>

