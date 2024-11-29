<form method="POST" id="property_category" action="{{route('property-categories.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$property_category->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$property_category->name}}">
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" required class="form-control"
                   placeholder="name" value="{{old('name', $property_category->name)}}">
            <span class="input-note text-danger" id="error-name"> </span>
            @error('name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>Short Name <span class="text-danger">*</span></label>
            <input type="text" name="short_name" id="short_name" required class="form-control"
                   placeholder="short_name" value="{{old('name', $property_category->short_name)}}">
            <span class="input-note text-danger" id="error-short_name"> </span>
            @error('short_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $property_category->description)}}</textarea>
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
</script>

