<form method="POST" action="{{route('awards.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$award->id}}">
    <input type="hidden" id="_name" name="me" value=" travel for {{$award->employee->FullName}}">
    <input type="hidden" id="travel_type_hidden" name="travel_type_hidden" value="{{$award->award_type_id}}">
    <div class="row clearfix">
        <div class="col-xl-10 col-lg-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="username" class="control-label">Employee</label>
                    <select name="employee" @if($award->terminated_employee != null)readonly @endif id="employee"
                            class="selectpicker form-control"
                            data-live-search="true" data-live-search-style="begins"
                            title='{{__('Selecting',['key'=>trans('file.Employee')])}}...'>
                        @foreach($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->FullName}}</option>
                        @endforeach
                    </select>
                    <span class="input-note text-danger" id="error-employee"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>{{__('Award Type')}}</label>
                    <select name="award_type" id="award_type" class="form-control selectpicker "
                            data-live-search="true" data-live-search-style="begins"
                            title='{{__('Award Type')}}'>
                        @foreach($award_types as $award_type)
                            <option value="{{$award_type->id}}">{{$award_type->award_name}}</option>
                        @endforeach
                    </select>
                    <span class="input-note text-danger" id="error-award_type"> </span>
                    @error('award_type')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>{{trans('file.Gift')}} *</label>
                    <input type="text" name="gift" id="gift" required class="form-control"
                           placeholder="{{trans('file.Gift')}}" value="{{old('gift', $award->gift)}}">
                    <span class="input-note text-danger" id="error-gift"> </span>
                    @error('gift')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>{{trans('file.Cash')}} *</label>
                    <input type="number" min="0" name="cash" id="cash" required class="form-control"
                           placeholder="{{trans('file.Cash')}}" value="{{old('cash', $award->cash)}}">
                    <span class="input-note text-danger" id="error-cash"> </span>
                    @error('cash')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="award_date" class="control-label">Award Date <span class="text-danger">*</span></label>
                    <input type="text" data-ignore="1" name="award_date" id="award_date" class="form-control date"
                           value="{{old('award_date', $award->award_date)}}">
                    <span class="input-note text-danger" id="error-award_date"> </span>
                    @error('award_date')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>{{__('Award Photo')}} *</label>
                    <input type="file"  name="award_photo" id="award_photo" required class="form-control"
                           placeholder="{{__('Award Photo')}}" value="{{old('award_photo', $award->award_photo)}}">
                    <span class="input-note text-danger" id="error-award_photo"> </span>
                    @error('award_photo')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                    @if(isset($award->award_photo))
                        <a target="_blank" href="{{ URL::to('/public') }}/uploads/award_photos/{{$award->award_photo}}">View Award Photo</a>
                    @endif
                </div>
                <div class="form-group col-12 col-md-8">
                    <label for="description" class="control-label">Award Information</label>
                    <textarea class="form-control" id="award_information" name="award_information"
                              rows="3">{{old('award_information', $award->award_information)}}</textarea>
                    <span class="input-note text-danger" id="error-award_information"> </span>
                    @error('award_information')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.controls")
                </div>
            </div>
        </div>
    </div>
</form>
<script>

    $('#employee').selectpicker('val', '{{$award->employee_id}}');
    $('#award_type').selectpicker('val', '{{$award->award_type_id}}');

    $(document).ready(function () {
        let date = $('.date');
        date.datepicker({
            format: '{{ env('Date_Format_JS')}}',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

