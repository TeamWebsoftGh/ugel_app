<form method="POST" id="office_shift" action="{{route('timesheet.office-shifts.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$office_shift->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$office_shift->shift_name}}">
    <div class="row">
        <div class="form-group col-12 col-md-12">
            <label>{{__('Shift Name')}} <span class="text-danger">*</span></label>
            <input type="text" name="shift_name" id="shift_name" required class="form-control"
                   value="{{old('shift_name', $office_shift->shift_name)}}">
            <span class="input-note text-danger" id="error-shift_name"> </span>
            @error('shift_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        @for ($i = 0; $i < count($days); $i++)
            <div class="form-group col-12 col-md-6">
                <label>{{__($days[$i]. ' In')}} </label>
                <input type="time" name="{{$dayKeys[$i]}}_in" id="{{$dayKeys[$i]}}_in" class="form-control time"
                       value="{{old($dayKeys[$i].'_in', $office_shift->{$dayKeys[$i].'_in'})}}">
                <span class="input-note text-danger" id="error-{{$dayKeys[$i]}}_in"> </span>
                @error($dayKeys[$i].'_in')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>

            <div class="form-group col-12 col-md-6">
                <label>{{__($days[$i]. ' Out')}} </label>
                <input type="time" name="{{$dayKeys[$i]}}_out" id="{{$dayKeys[$i]}}_out" class="form-control time"
                       value="{{old($dayKeys[$i].'_out', $office_shift->{$dayKeys[$i].'_out'})}}">
                <span class="input-note text-danger" id="error-{{$dayKeys[$i]}}_out"> </span>
                @error($dayKeys[$i].'_out')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
        @endfor
        <div class="form-group col-12">
            @include("shared.save-button")
        </div>
    </div>
</form>

