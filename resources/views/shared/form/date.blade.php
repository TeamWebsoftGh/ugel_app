<div class="form-group {{$class??"col-6 col-md-4 "}} ">
    <label for="{{ $name }}">{{ $label ?? 'Date' }}</label>
    <input type="date" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $defaultValue) }}">
    <span class="input-note text-danger" id="error-{{ $name }}"> </span>
    @error($name)
    <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

