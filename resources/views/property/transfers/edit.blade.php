<form method="POST" id="transfer" action="{{route('property.transfers.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$transfer->id}}">
    <input type="hidden" id="_name" name="me" value=" transfer for {{$transfer->employee->FullName}}">
    <div class="row">
        @include("shared.form.select-employee", ["employee_id" => $transfer->employee_id])
        <div class="form-group col-6 col-md-4">
            <label>{{__('From Department')}}</label>
            <select name="current_department" readonly id="from_department_id" class="form-control selectpicker "
                    data-live-search="true" title='{{__('From Department')}}'>
                @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-current_department"> </span>
            @error('current_department')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('New Department')}}</label>
            <select name="to_department" id="to_department_id" class="selectpicker form-control"
                    data-live-search="true" title='{{__('Selecting',['key'=>__('New Department')])}}...'>
                @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-to_department"> </span>
            @error('to_department')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Current Branch')}}</label>
            <select name="current_branch" readonly id="current_branch" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Current Branch')}}'>
                @foreach($branches as $location)
                    <option value="{{$location->id}}">{{$location->branch_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-current_branch"> </span>
            @error('current_branch')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('New Branch')}}</label>
            <select name="to_location" id="to_location_id" class="selectpicker form-control"
                    data-live-search="true" title='{{__('Selecting',['key'=>__('To Location')])}}...'>
                @foreach($locations as $location)
                    <option value="{{$location->id}}">{{$location->branch_name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-to_location"> </span>
            @error('to_location')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="transfer_date" class="control-label">Transfer Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="transfer_date" id="transfer_date" class="form-control date"
                   value="{{old('transfer_date', $transfer->transfer_date)}}">
            <span class="input-note text-danger" id="error-transfer_date"> </span>
            @error('transfer_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="notice_date" class="control-label">Notice Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="notice_date" id="notice_date" class="form-control date"
                   value="{{old('notice_date', $transfer->notice_date)}}">
            <span class="input-note text-danger" id="error-notice_date"> </span>
            @error('notice_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $transfer->description)}}</textarea>
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
    $(document).ready(function () {
        $('#employee').selectpicker('val', '{{$transfer->employee_id}}');
        $('#from_department_id').selectpicker('val', '{{$transfer->from_department_id}}');
        $('#to_department_id').selectpicker('val', '{{$transfer->to_department_id}}');
        $('#current_branch').selectpicker('val', '{{$transfer->from_branch_id}}');
        $('#to_location_id').selectpicker('val', '{{$transfer->to_branch_id}}');
        $('#employee').change(function () {
            $('#to_department_id').selectpicker();
            if ($(this).val() !== '') {
                let value = $(this).val();
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_employee_details') }}",
                    method: "POST",
                    data: { value: value, _token: _token },
                    success: function (result) {
                        console.log(result['department_id']);
                        console.log(result['branch_id']);
                        $('#from_department_id').selectpicker('val', result['department_id']+'');
                        $('#current_branch').selectpicker('val', result['branch_id']+'');
                    }
                });
            }
        });
    });
</script>

