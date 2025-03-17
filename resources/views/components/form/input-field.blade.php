<div class="form-group {{ $class }}">
    <label for="{{ $id ?? $name }}" class="control-label">
        {{ $label }} @if ($required)<span class="text-danger">*</span>@endif
    </label>

    @if ($type == 'select')
        <select name="{{ $name }}" id="{{ $id }}"
                class="form-control {{ isset($selectpicker) && $selectpicker ? 'selectpicker' : '' }}"
            {{ isset($liveSearch) && $liveSearch ? 'data-live-search="true"' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}>
            <option value="">Select {{ $label }}</option> <!-- Ensure default option -->
            @foreach ($options as $key => $v)
                <option value="{{ $key }}"
                    {{ (old($name) !== null ? old($name) : $value) == $key ? 'selected' : '' }}>
                    {{ $v }}
                </option>
            @endforeach
        </select>

    @elseif ($type == 'textarea')
        <textarea class="form-control" id="{{ $id }}" {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        name="{{ $name }}" rows="{{$rows}}">{{old($name, $value)}}</textarea>
    @elseif ($type == 'file')
        <input type="file" name="{{ $name }}" id="{{ $id ?? $name }}" class="form-control dropify"
               data-default-file="{{ !empty($value) ? asset($value) : '' }}"
               {{ $required ? 'required' : '' }}
               accept="image/*">

    @elseif ($type == 'radio')
        <div class="form-check">
            @foreach ($options as $key => $v)
                <input type="radio" name="{{ $name }}" id="{{ $id . '-' . $key }}" value="{{ $key }}" class="form-check-input"
                    {{ (old($name, $value) == $key) ? 'checked' : '' }} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
                    {{ $required ? 'required' : '' }}>
                <label class="form-check-label" for="{{ $id . '-' . $key }}">{{ $v }}</label><br>
            @endforeach
        </div>

    @elseif ($type == 'checkbox')
        <div class="form-check">
            <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" value="1" class="form-check-input"
                {{ (old($name, $value) == '1') ? 'checked' : '' }} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
                {{ $required ? 'required' : '' }}>
            <label class="form-check-label" for="{{ $id ?? $name }}">{{ $label }}</label>
        </div>

    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" class="form-control" value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}>
    @endif

    <span class="input-note text-danger" id="error-{{ $name }}"></span>
</div>
