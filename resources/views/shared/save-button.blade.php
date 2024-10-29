
@if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
    <button type="submit" class="btn btn-success save_dt_btn"><i class="fa fa-save"></i> Save</button>
@endif

