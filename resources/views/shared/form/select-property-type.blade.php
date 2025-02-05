<div class="form-group {{$class??"col-6 col-md-4 "}}">
    <label for="{{ $name ?? 'property_type_id' }}" class="control-label">
        {{ $label ?? 'Property Type' }} <span class="text-danger">*</span>
    </label>
    <select name="{{ $name ?? 'property_type_id' }}"
            id="{{ $name ?? 'property_type_id' }}"
            class="selectpicker form-control"
            data-live-search="true"
            title='{{ __('Selecting', ['key' => __('Property Type')]) }}...'
        {{ $property_type_id ? 'readonly' : '' }}>
        @foreach($property_types as $type)
            <option value="{{ $type->id }}"
                {{ old($name ?? 'property_type_id', $property_type_id) == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
    <span class="input-note text-danger" id="error-{{ $name ?? 'property_type_id' }}"> </span>
</div>
