<form method="POST" action="{{route('enquiries.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$enquiry->id}}">
    <input type="hidden" id="_name" name="me" value="{{$enquiry->name}}">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $enquiry->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $enquiry->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Email </label>
                    <input type="email" name="email" value="{{old('email', $enquiry->email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email"> </span>
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" value="{{old('subject', $enquiry->subject)}}" class="form-control">
                    <span class="input-note text-danger" id="error-subject"> </span>
                    @error('subject')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-12">
                    <label for="note" class="control-label">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message"
                              rows="3">{{old('message', $enquiry->message)}}</textarea>
                    <span class="input-note text-danger" id="error-message"> </span>
                    @error('message')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    @include("shared.save-button")
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $('#status').selectpicker('val', '{{$enquiry->is_active}}');
</script>
