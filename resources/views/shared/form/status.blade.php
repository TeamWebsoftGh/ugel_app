<div class="form-group @if(isset($class)) {{$class}} @else col-6 col-md-4 @endif">
    <label for="is_active" class="control-label">Status <span class="text-danger">*</span></label>
    <select name="is_active" id="is_active" class="selectpicker form-control"
            data-live-search="true"
            title='{{__('Selecting',['key'=>__('Status')])}}...'>
        <option value="1" @selected(old('is_active', $status) == 1)>Active</option>
        <option value="0" @selected(old('is_active', $status) == 0)>Inactive</option>
    </select>
    <span class="input-note text-danger" id="error-is_active"> </span>
</div>
