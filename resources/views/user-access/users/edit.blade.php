<form method="POST" action="{{route('admin.users.store')}}" id="users" enctype="multipart/form-data">
    <p>All fields with <span class="text-danger">*</span> are required.</p>
    @csrf
    <input type="hidden" id="_id" name="id" value="{{$user->id}}">
    <input type="hidden" id="_name" name="me" value="{{$user->fullname}}">
    <div class="row clearfix">
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{old('name', $user->name)}}" class="form-control">
                    <span class="input-note text-danger" id="error-first_name"> </span>
                    @error('name')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{old('email', $user->email)}}" class="form-control">
                    <span class="input-note text-danger" id="error-email"> </span>
                    @error('email')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="username" class="control-label">Username <span class="text-danger">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" value="{{$user->username}}">
                    <span class="input-note text-danger" id="error-username"> </span>
                </div>
                <div class="form-group col-6 col-md-6">
                    <label for="name" class="control-label">Phone Number</label>
                    <input type="tel" name="phone_number" value="{{old('phone_number', $user->phone_number)}}" class="form-control">
                    <span class="input-note text-danger" id="error-phone_number"> </span>
                    @error('phone_number')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>

                <div class="form-group col-6 col-md-6">
                    <label class="control-label">Role <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker" data-live-search="true" data-msg="Required" name="role">
                        <option value="" disabled selected>Nothing Selected</option>
                        @forelse($roles as $role)
                            <option value="{{$role->id}}" @if(old('role', $user->RoleId) == $role->id) selected="selected" @endif>{{$role->display_name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <span class="input-note text-danger" id="error-role"> </span>
                    @error('role')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-6">
                    <label>Login Status <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-control selectpicker">
                        <option value="0" @if($user->is_active == 0) selected="selected" @endif>Disable</option>
                        <option value="1" @if($user->is_active == 1) selected="selected" @endif>Enable</option>
                    </select>
                    @error('status')
                    <span class="input-note text-danger">{{ $message }} </span>
                    @enderror
                </div>
                @if(isset($user->id))
                    <div class="form-group col-6 col-md-6">
                        <label>Last Login </label>
                        <input type="text" class="form-control ignore" data-ignore="1" value="{{$user->last_login_date??"Never"}}" readonly>
                    </div>
                @endif
                <div class="form-group col-12">
                    @include("shared.new-controls")
                    @if($user->id && user()->can('update-users'))
                        <button type="button" class="btn btn-secondary edit_btn btn-sm" onclick="ResetPassword('{{$user->full_name}}', '{{route('admin.users.reset-password', $user->id)}}')"><i class="fa fa-chain"></i> Reset Password</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cover">Cover Image </label>
                <input type="file" name="image" id="image" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg" data-default-file="{{ asset("$user->UserImage") }}" >
                @if ($errors->has('image'))
                    <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</form>
