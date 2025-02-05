<form method="POST" action="{{route('admin.users.store')}}" id="user" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$user->id}}">
    <input type="hidden" id="_name" name="me" value="{{$user->fullname}}">
    <div class="row clearfix">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{old('first_name', $user->first_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-first_name"> </span>
                    @error('first_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="last_name" class="control-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" value="{{old('last_name', $user->last_name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-last_name"> </span>
                    @error('last_name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Email </label>
                    <input type="email" name="email" value="{{old('email', $user->email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email"> </span>
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="username" class="control-label">Username <span class="text-danger">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" value="{{$user->username}}">
                    <span class="input-note text-danger" id="error-username"> </span>
                </div>
                <div class="form-group col-6 col-md-4">
                    <label for="name" class="control-label">Phone Number</label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $user->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-4">
                    <label class="control-label">Role <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker" data-live-search="true" data-msg="Required" name="role">
                        <option value="" disabled selected>Nothing Selected</option>
                        @forelse($roles as $role)
                            <option value="{{$role->name}}" @if(old('role', $user->RoleId) == $role->id) selected="selected" @endif>{{$role->display_name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-role"> </span>
                    @error('role')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-4">
                    <label>Login Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($user->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($user->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('is_active')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                @if(isset($user->id))
                    <div class="form-group col-6 col-md-4">
                        <label>Last Login </label>
                        <input type="text" class="form-control ignore" data-ignore="1" value="{{$user->last_login_date??"Never"}}" readonly>
                    </div>
                @endif
                <div class="form-group col-12">
                    @include("shared.save-button")
                    @if($user->id && user()->can('update-users'))
                        <button type="button" class="btn btn-secondary" onclick="ResetPassword('{{$user->full_name}}', '{{route('admin.users.reset-password', $user->id)}}')"><i class="fa fa-chain"></i> Reset Password</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
