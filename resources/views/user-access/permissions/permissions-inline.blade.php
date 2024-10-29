
<label class="col-form-label">Select Permissions</label>
<div class="row">
    @foreach($permissions as $permission)
        <div class="col-6 col-md-3">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" name="permissions[]" class="custom-control-input" value="{{ $permission->id }}"
                       @if(isset($attachedPermissionsArrayIds) && in_array($permission->id, $attachedPermissionsArrayIds))checked="checked" @endif>
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">{{ $permission->display_name }}</span>
            </label>
        </div>
    @endforeach
</div>
