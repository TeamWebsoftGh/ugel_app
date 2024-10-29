<form method="POST" id="complaint" action="{{route('property.complaints.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$complaint->id}}">
    <input type="hidden" id="_name" name="me" value=" complaint by {{$complaint->complaint_from_employee->FullName}}">
    <div class="row clearfix">
        @include("shared.form.select-employee", ["employee_id" => $complaint->complaint_from, "name" => "complaint_from","label" => "Complaint From"])
        @include("shared.form.select-employee", ["employee_id" => $complaint->complaint_against, "name" => "complaint_against", "label" => "Complaint Against"])

        <div class="form-group col-6 col-md-4">
            <label for="complaint_date" class="control-label">Complaint Date <span class="text-danger">*</span></label>
            <input type="date" name="complaint_date" id="complaint_date" class="form-control date"
                   value="{{old('complaint_date', $complaint->complaint_date)}}">
            <span class="input-note text-danger" id="error-notice_date"> </span>
            @error('complaint_date')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Complaint Title')}} <span class="text-danger">*</span></label>
            <input type="text" name="complaint_title" id="complaint_title" required class="form-control"
                   placeholder="{{__('Complaint Title')}}" value="{{old('complaint_title', $complaint->complaint_title)}}">
            <span class="input-note text-danger" id="error-complaint_title"> </span>
            @error('complaint_title')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $complaint->description)}}</textarea>
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
