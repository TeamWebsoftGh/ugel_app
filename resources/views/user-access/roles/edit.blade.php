<form method="POST" action="{{route('admin.roles.store')}}" >
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$role->id}}">
    <input type="hidden" id="_name" name="me" value="{{$role->display_name}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $role->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="display_name" class="control-label">Display Name <span class="text-danger">*</span></label>
                    <input type="text" name="display_name" value="{{old('display_name', $role->display_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-display_name"> </span>
                    @error('display_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Status</label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($role->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($role->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-12">
                    <label class="col-form-label">Select Permissions</label>
                    @php
                        $groupedPermissions = collect($permissions)->groupBy(function($perm) {
                            // Get last word from display_name as model (e.g., 'Maintenance Requests')
                            $words = explode(' ', $perm->display_name);
                            array_shift($words); // Remove action (Create/Read/etc.)
                            return implode(' ', $words);
                        });
                    @endphp

                    @foreach($groupedPermissions as $model => $perms)
                        @php $modelClass = strtolower(str_replace(' ', '_', $model)); @endphp
                        <div class="card mb-3 border">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <strong>{{ $model }}</strong>
                                <div>
                                    <input type="checkbox" class="select-all-model" data-model="{{ $modelClass }}" />
                                    <label class="mb-0">Select All</label>
                                </div>
                            </div>
                            <div class="card-body row">
                                @foreach($perms as $perm)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox {{ $modelClass }}" type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_{{ $perm->id }}"
                                                {{ isset($attachedPermissionsArrayIds) && in_array($perm->id, $attachedPermissionsArrayIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                {{ $perm->display_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @error('permissions')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-12">
                    @include("shared.new-controls")
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        $(document).on('change', '.select-all-model', function () {
            const model = $(this).data('model');
            const checked = $(this).is(':checked');
            $('.' + model).prop('checked', checked);
        });

        $(document).on('change', '.permission-checkbox', function () {
            const modelClass = Array.from(this.classList).find(cls => cls !== 'permission-checkbox');
            const all = $('.' + modelClass);
            const allChecked = all.length === all.filter(':checked').length;
            $('.select-all-model[data-model="' + modelClass + '"]').prop('checked', allChecked);
        });
    </script>
@endpush
