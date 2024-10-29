<div class="form-group col-6 col-md-4">
    <label>{{__('Attachment')}} </label>
    <input type="file" name="attachment" id="attachment" class="form-control"
           placeholder="{{__('Attachment')}}">
    <span class="input-note text-danger" id="error-attachment"> </span>
    @error('attachment')
    <span class="input-note text-danger">{{ $message }} </span>
    @enderror
    @if(isset($attachment))
        <a target="_blank" href="{{ asset("uploads/$attachment") }}">View Attachment</a>
    @endif
</div>
