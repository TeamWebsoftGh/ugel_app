<div class="form-group {{ $class }}">
    <label for="{{ $id ?? $name }}" class="control-label">
        {{ $label }} @if ($attributes->has('required'))<span class="text-danger">*</span>@endif
    </label>

    @if ($type === 'select')
        <select name="{{ $name }}" id="{{ $id ?? $name }}" data-live-search="true"
            {{ $attributes->merge(['class' => 'form-control selectpicker']) }}>
            <option value="">Select {{ $label }}</option>
            @foreach ($options as $key => $v)
                <option value="{{ $key }}" {{ (old($name) ?? $value) == $key ? 'selected' : '' }}>
                    {{ $v }}
                </option>
            @endforeach
        </select>

    @elseif ($type === 'multiselect')
        <select name="{{ $name }}[]" id="{{ $id ?? $name }}" multiple data-live-search="true"
            {{ $attributes->merge(['class' => 'form-control selectpicker']) }}>
            @foreach ($options as $key => $v)
                <option value="{{ $key }}" {{ in_array($key, (array)(old($name, $value ?? []))) ? 'selected' : '' }}>
                    {{ $v }}
                </option>
            @endforeach
        </select>

    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $id ?? $name }}"
                  rows="{{ $rows ?? 3 }}"
                  {{ $attributes->merge(['class' => 'form-control']) }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'summernote')
        <textarea name="{{ $name }}" id="{{ $id ?? $name }}"
              {{ $attributes->merge(['class' => 'form-control summernote']) }}>{{ old($name, $value) }}</textarea>

    @elseif ($type === 'file')
        <input type="file" name="{{ $name }}" id="{{ $id ?? $name }}"
               data-default-file="{{ !empty($value) ? asset($value) : '' }}"
            {{ $attributes->merge(['class' => 'form-control dropify']) }}>
    @elseif ($type === 'multifile')
        <input type="file" name="{{ $name }}[]" id="{{ $id ?? $name }}"
               multiple
            {{ $attributes->merge(['class' => 'form-control']) }}>
        @if(isset($value)  && count($value))
            <div class="mt-2">
                <label class="form-label">Existing Attachments:</label>
                <ul class="list-unstyled">
                    @foreach($value as $attachment)
                        <li class="mb-1 d-flex align-items-center s_attach justify-content-between">
                            <a href="{{ asset($attachment->file_path) }}" target="_blank">{{ basename($attachment->file_path) }}</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-icon delete-attachment"
                                    data-id="{{ $attachment->id }}"
                                    data-url="{{ route('attachments.destroy', $attachment->id) }}">
                                <i class="mdi mdi-close"></i>
                            </button>

                        </li>
                    @endforeach
                </ul>
            </div>
        @endif


    @elseif ($type === 'date')
        <input type="date" name="{{ $name }}" id="{{ $id ?? $name }}"
               value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'form-control date']) }}>

    @elseif ($type === 'radio')
        <div class="form-check">
            @foreach ($options as $key => $v)
                <input type="radio" name="{{ $name }}" id="{{ $id . '-' . $key }}" value="{{ $key }}"
                    {{ (old($name, $value) == $key) ? 'checked' : '' }}
                    {{ $attributes->merge(['class' => 'form-check-input']) }}>
                <label class="form-check-label" for="{{ $id . '-' . $key }}">{{ $v }}</label><br>
            @endforeach
        </div>

    @elseif ($type === 'checkbox')
        <div class="form-check">
            <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" value="1"
                {{ (old($name, $value) == '1') ? 'checked' : '' }}
                {{ $attributes->merge(['class' => 'form-check-input']) }}>
            <label class="form-check-label" for="{{ $id ?? $name }}">{{ $label }}</label>
        </div>

    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'form-control']) }}>
    @endif

    <span class="input-note text-danger" id="error-{{ $name }}"></span>
</div>
