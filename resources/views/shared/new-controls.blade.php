{{--@if(\Illuminate\Support\Facades\Gate::check('create')||\Illuminate\Support\Facades\Gate::check('update'))--}}
{{--    <button type="submit" class="btn btn-success btn-sm save_btn"><i class="fa fa-floppy-o"></i> Save</button>--}}
{{--@endif--}}
{{--@if(user()->hasPermission('create-'.$model))--}}
{{--    <button type="button" class="btn btn-warning btn-sm new_btn"><i class="fa fa-plus"></i> New</button>--}}
{{--    <button type="button" class="btn btn-default btn-sm new_btn hide"><i class="fas fa-plus"></i> New Version</button>--}}
{{--@endcan--}}
{{--<button type="button" class="btn btn-default btn-sm cancel_btn" style="display:none"><i class="fa fa-times"></i> Cancel</button>--}}
{{--@can('update')--}}
{{--    <button type="button" class="btn btn-info btn-sm edit_btn"><i class="fa fa-edit"></i> Edit</button>--}}
{{--@endcan--}}
{{--@can('delete, guard:tasks')--}}
{{--    <button type="button" class="btn btn-danger btn-sm delete_btn"><i class="fa fa-times"></i> Delete</button>--}}
{{--@endcan--}}
@if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
    <button type="submit" class="btn btn-success btn-sm save_btn"><i class="fa fa-save"></i> Save</button>
@endif
@if(user()->can('create-'.get_permission_name()))
    <button type="button" class="btn btn-secondary btn-sm new_btn"><i class="fa fa-plus"></i> New</button>
@endif
@if(user()->canany(['create-'.get_permission_name(), 'update-'.get_permission_name()]))
    <button type="button" class="btn btn-warning btn-sm cancel_btn" style="display:none"><i class="fa fa-times"></i> Cancel</button>
@endif
@if(user()->can('update-'.get_permission_name()))
    <button type="button" class="btn btn-info btn-sm edit_btn"><i class="fa fa-edit"></i> Edit</button>
@endif
@if(user()->can('delete-'.get_permission_name()))
    <button type="button" class="btn btn-danger btn-sm delete_btn"><i class="fa fa-times"></i> Delete</button>
@endif

