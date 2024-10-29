<form method="POST" id="travel" action="{{route('property.travels.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$travel->id}}">
    <input type="hidden" id="_name" name="me" value=" travel for {{$travel->employee->FullName}}">
    <input type="hidden" id="travel_type_hidden" name="travel_type_hidden" value="{{$travel->travel_type_id}}">
    <div class="row">
        @include("shared.form.select-employee", ["employee_id" => $travel->employee_id])
        <div class="form-group col-6 col-md-4">
            <label>{{__('Arrangement Type')}}</label>
            <select name="travel_type_id" id="travel_type_id" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Arrangement Type')}}'>
                @foreach($travel_types as $travel_type)
                    <option value="{{$travel_type->id}}" @selected(old('travel_type_id', $travel->travel_type_id) == $travel_type->id)>{{$travel_type->arrangement_type}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-travel_type_id"> </span>
            @error('travel_type_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Purpose Of Visit')}} *</label>
            <input type="text" name="purpose_of_visit" id="purpose_of_visit" required class="form-control"
                   placeholder="{{__('Purpose Of Visit')}}" value="{{old('purpose_of_visit', $travel->purpose_of_visit)}}">
            <span class="input-note text-danger" id="error-purpose_of_visit"> </span>
            @error('purpose_of_visit')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Place Of Visit')}} *</label>
            <input type="text" name="place_of_visit" id="place_of_visit" required class="form-control"
                   placeholder="{{__('Place Of Visit')}}" value="{{old('place_of_visit', $travel->place_of_visit)}}">
            <span class="input-note text-danger" id="error-place_of_visit"> </span>
            @error('place_of_visit')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="start_date" class="control-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="start_date" id="start_date" class="form-control date"
                   value="{{old('start_date', $travel->start_date)}}">
            <span class="input-note text-danger" id="error-start_date"> </span>
            @error('start_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="end_date" class="control-label">End Date <span class="text-danger">*</span></label>
            <input type="date" data-ignore="1" name="end_date" id="end_date" class="form-control date"
                   value="{{old('end_date', $travel->end_date)}}">
            <span class="input-note text-danger" id="error-end_date"> </span>
            @error('end_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Expected Budget')}} *</label>
            <input type="number" min="0" name="expected_budget" id="expected_budget" required class="form-control"
                   placeholder="{{__('Expected Budget')}}" value="{{old('expected_budget', $travel->expected_budget)}}">
            <span class="input-note text-danger" id="error-expected_budget"> </span>
            @error('expected_budget')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Actual Budget')}} *</label>
            <input type="number" min="0" name="actual_budget" id="actual_budget" required class="form-control"
                   placeholder="{{__('Actual Budget')}}" value="{{old('actual_budget', $travel->actual_budget)}}">
            <span class="input-note text-danger" id="error-actual_budget"> </span>
            @error('actual_budget')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Travel Mode')}}</label>
            <select name="travel_mode" id="travel_mode" class="form-control selectpicker "
                    data-live-search="true" title='{{__('Travel Mode')}}'>
                @foreach(\App\Constants\Constants::TRAVEL_MODES as $key => $travel_mode)
                    <option value="{{$key}}" @selected(old('travel_mode', $travel->travel_mode) == $key)>{{$travel_mode}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-travel_mode"> </span>
            @error('travel_mode')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{trans('file.Status')}}</label>
            <select name="status" id="status" class="form-control selectpicker "
                    data-live-search="true"
                    title='{{__('Selecting',['key'=>trans('file.Status')])}}...'>
                <option value="pending" @selected(old('status', $travel->status) == "pending")>{{__('Pending')}}</option>
                <option value="approved" @selected(old('status', $travel->status) == "approved")>{{__('Approved')}}</option>
                <option value="rejected" @selected(old('status', $travel->status) == "rejected")>{{__('Rejected')}}</option>
            </select>
            <span class="input-note text-danger" id="error-status"> </span>
            @error('status')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $travel->description)}}</textarea>
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
        let date = $('.date');
        date.datepicker({
            format: '{{ env('Date_Format_JS')}}',
            autoclose: true,
            todayHighlight: true
        });
    });
    $('#arrangement_type').selectpicker('val', {{$travel->travel_type}});
    $('#employee').selectpicker('val', {{$travel->employee_id}});
    $('#travel_mode').selectpicker('val', '{{$travel->travel_mode}}');
    $('#status').selectpicker('val', '{{$travel->status}}');
</script>

