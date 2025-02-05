<div class="form-group {{$class??"col-6 col-md-4 "}}">
    <label for="{{ $name ?? 'is_active' }}" class="control-label">
        {{ $label ?? 'Status' }} <span class="text-danger">*</span>
    </label>
    <select name="{{ $name ?? 'is_active' }}"
            id="{{ $name ?? 'is_active' }}"
            class="selectpicker form-control"
            data-live-search="true"
            title='{{ __('Selecting', ['key' => __('Status')]) }}...'
        {{ $status ? 'readonly' : '' }}>
        <option value="0" @if($status == 0) selected="selected" @endif>Disable</option>
        <option value="1" @if($status == 1) selected="selected" @endif>Enable</option>
    </select>
    <span class="input-note text-danger" id="error-{{ $name ?? 'is_active' }}"> </span>
</div>
