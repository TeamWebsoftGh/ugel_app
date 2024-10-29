<form method="POST" id="property_type" action="{{route('property-types.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$property_type->id}}">
    <input type="hidden" id="_name" name="me" value=" award for {{$property_type->name}}">
    <div class="row">
        <div class="form-group col-6 col-md-4">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" required class="form-control"
                   placeholder="name" value="{{old('name', $property_type->name)}}">
            <span class="input-note text-danger" id="error-name"> </span>
            @error('name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>Short Name <span class="text-danger">*</span></label>
            <input type="text" name="short_name" id="short_name" required class="form-control"
                   placeholder="short_name" value="{{old('name', $property_type->short_name)}}">
            <span class="input-note text-danger" id="error-short_name"> </span>
            @error('short_name')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-6 col-md-4">
            <label>{{__('Property Category')}} <span class="text-danger">*</span></label>
            <select name="property_category_id" id="property_category_id" class="form-control selectpicker "
                    data-live-search="true"
                    title='{{__('Property Category')}}'>
                @foreach($categories as $cat)
                    <option value="{{$cat->id}}" @selected(old('property_category_id', $property_type->property_category_id) == $cat->id)>{{$cat->name}}</option>
                @endforeach
            </select>
            <span class="input-note text-danger" id="error-property_category_id"> </span>
            @error('property_category_id')
            <span class="input-note text-danger">{{ $message }} </span>
            @enderror
        </div>
        <div class="form-group col-12 col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{old('description', $property_type->description)}}</textarea>
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

