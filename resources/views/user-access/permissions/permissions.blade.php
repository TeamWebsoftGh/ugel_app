
<h5 class="checkbox_header_bottom">Select Permissions for Role</h5>
<div class="left_align custom-controls-stacked m-t-10">
    @foreach($permissions as $permission)
        <label class="custom-control custom-checkbox">
            <input type="checkbox" name="permissions[]" class="custom-control-input" value="{{ $permission->id }}"
                   @if(isset($selectedIds) && in_array($permission->id, $selectedIds))checked="checked" @endif>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">{{ $permission->display_name }}</span>
        </label>
    @endforeach
</div>
