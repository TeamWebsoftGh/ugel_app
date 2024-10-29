<form method="POST" id="holiday" action="{{route('timesheet.holidays.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$holiday->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$holiday->event_name}}">
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label>{{__('Event Name')}} <span class="text-danger">*</span></label>
            <input type="text" name="event_name" id="event_name" required class="form-control"
                   value="{{old('event_name', $holiday->event_name)}}">
            <span class="input-note text-danger" id="error-event_name"> </span>
            @error('event_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Start Date')}} <span class="text-danger">*</span></label>
            <input type="text" name="start_date" class="form-control date" id="start_date" value="{{ old("start_date", $holiday->start_date) }}">
            <span class="input-note text-danger" id="error-start_date"> </span>
            @error("start_date")
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('End Date')}} <span class="text-danger">*</span></label>
            <input type="text" name="end_date" class="form-control date" id="end_date" value="{{ old("end_date", $holiday->end_date) }}">
            <span class="input-note text-danger" id="error-end_date"> </span>
            @error("end_date")
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        @include("shared.form.status", ["status" => $holiday->is_active])
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>

