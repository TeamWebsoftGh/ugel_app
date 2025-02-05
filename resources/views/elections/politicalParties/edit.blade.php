<form method="POST" action="{{route('political-parties.store')}}" id="political_party" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$political_party->id}}">
    <input type="hidden" id="_name" name="me" value="{{$political_party->ame}}">
    <div class="row clearfix">
        <div class="col-md-4">
            <div class="form-group">
                <label for="image" class="control-label">Image <span class="text-danger">*</span></label>
                <input type="file" name="image" id="image" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg jpeg" data-default-file="{{ asset("uploads/$political_party->image") }}" >
                <span class="input-note text-danger" id="error-image"> </span>
                @error('image')
                <span class="input-note text-danger">{{ $message }} </span>
                @enderror
            </div>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $political_party->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="code" class="control-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{old('code', $political_party->code)}}" class="form-control">
                    <span class="input-note text-danger" id="error-code"> </span>
                    @error('code')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-12 col-md-6">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($political_party->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($political_party->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    <span class="input-note text-danger" id="error-is_active"> </span>
                    @error('is_active')
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
<script>$('.dropify').dropify();</script>

