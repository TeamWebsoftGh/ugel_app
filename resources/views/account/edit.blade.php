@extends('layouts.admin.main')

@section('title', 'Edit Profile')
@section('page-title', 'User Profile')
@section('breadcrumb')
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Update User Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.account.update')}}" >
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <div class="row clearfix">
                            <div class="col-md-10 col-sm-12">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="control-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" value="{{old('first_name', $user->first_name)}}" class="form-control">
                                        @error('first_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email" class="control-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control" value="{{old('last_name', $user->last_name)}}">
                                        @error('last_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email" class="control-label">Other Names</label>
                                        <input type="text" name="other_names" class="form-control" value="{{old('other_names', $user->other_names)}}">
                                        @error('other_names')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name" class="control-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{old('email', $user->email)}}" class="form-control">
                                        @error('email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email" class="control-label">Username</label>
                                        <input type="text" name="username" disabled class="form-control" value="{{old('username', $user->username)}}">
                                        @error('username')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name" class="control-label">Phone Number</label>
                                        <input type="tel" name="phone_number" value="{{old('phone_number', $user->phone_number)}}" class="form-control">
                                        @error('phone_number')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name" class="control-label">Role</label>
                                        <input type="text" value="{{$user->roleName}}" disabled class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gender</label>
                                        <br />
                                        <label class="fancy-radio">
                                            <input type="radio" name="gender" value="Male" @if(old('gender', $user->gender) == "Male") checked @endif>
                                            <span><i></i>Male</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input type="radio" name="gender" value="Female" @if(old('gender', $user->gender) == "Female") checked @endif>
                                            <span><i></i>Female</span>
                                        </label>
                                        @error('gender')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

@endsection
