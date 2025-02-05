<div class="form-group {{$class??"col-6 col-md-4 "}}">
    <label for="{{ $name ?? 'employee_id' }}" class="control-label">
        {{ $label ?? 'Employee' }} <span class="text-danger">*</span>
    </label>
    <select name="{{ $name ?? 'employee_id' }}"
            id="{{ $name ?? 'employee_id' }}"
            class="selectpicker form-control"
            data-live-search="true"
            title='{{ __('Selecting', ['key' => __('Employee')]) }}...'
        {{ $employee_id ? 'readonly' : '' }}>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}"
                {{ old($name ?? 'employee_id', $employee_id) == $employee->id ? 'selected' : '' }}>
                {{ $employee->FullName }} - {{ $employee->staff_id }}
            </option>
        @endforeach
    </select>
    <span class="input-note text-danger" id="error-{{ $name ?? 'employee_id' }}"> </span>
</div>
