@extends('layouts.main')

@section('title', 'Update General Settings')
@section('page-title', 'General Settings')

@section('content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4>Update General Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.configurations.settings.update')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <div class="row clearfix">
                            <div class="col-md-8 col-lg-9">
                                <div class="row">
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="app_name" class="control-label">Application Name <span class="text-danger">*</span></label>
                                        <input type="text" id="app_name" name="app_name" value="{{old('app_name', $setting->app_name)}}" class="form-control">
                                        @error('app_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control" value="{{old('company_name', $setting->company_name)}}">
                                        @error('company_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Company Email</label>
                                        <input type="email" name="company_email" class="form-control" value="{{old('company_email', $setting->company_email)}}">
                                        @error('company_email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Alternate Company Email</label>
                                        <input type="email" name="company_email_alt" class="form-control" value="{{old('company_email_alt', $setting->company_email_alt)}}">
                                        @error('company_email_alt')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Phone Number</label>
                                        <input type="tel" name="company_phone_number" value="{{old('company_phone_number', $setting->company_phone_number)}}" class="form-control">
                                        @error('company_phone_number')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Alternate Company Phone Number</label>
                                        <input type="tel" name="company_phone_number_alt" value="{{old('company_phone_number_alt', $setting->company_phone_number_alt)}}" class="form-control">
                                        @error('company_phone_number_alt')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Address <span class="text-danger">*</span></label>
                                        <input type="text" name="company_address" value="{{old('company_address', $setting->company_address)}}" class="form-control">
                                        @error('company_address')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Address(Line 2)</label>
                                        <input type="tel" name="address_line_2" value="{{old('address_line_2', $setting->address_line_2)}}" class="form-control">
                                        @error('address_line_2')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Report Start Year</label>
                                        <input type="number" name="report_start_year" class="form-control" value="{{old('report_start_year', $setting->report_start_year)}}">
                                        @error('report_start_year')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="vision" class="control-label">Vision</label>
                                        <textarea class="form-control" rows="6" name="vision">{{old('vision', $setting->vision)}}</textarea>
                                        @error('vision')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="mission" class="control-label">Mission</label>
                                        <textarea class="form-control" rows="6" name="mission">{{old('mission', $setting->mission)}}</textarea>
                                        @error('mission')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="description" class="control-label">Description</label>
                                        <textarea class="form-control" rows="6" name="description">{{old('description', $setting->description)}}</textarea>
                                        @error('description')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-3 col-xl-2">
                                        <label>Verify Emails</label>
                                        <br />
                                        <label class="fancy-radio">
                                            <input type="radio" name="verify_email" value="1" @if(old('verify_email', $setting->verify_email) == "1") checked @endif>
                                            <span><i></i>Yes</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input type="radio" name="verify_email" value="0" @if(old('verify_email', $setting->verify_email) == "0") checked @endif>
                                            <span><i></i>No</span>
                                        </label>
                                        @error('verify_email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3 col-xl-3">
                                        <label>Expire Passwords</label>
                                        <br />
                                        <label class="fancy-radio">
                                            <input type="radio" name="expire_passwords" value="1" @if(old('expire_passwords', $setting->expire_passwords) == "1") checked @endif>
                                            <span><i></i>Yes</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input type="radio" name="expire_passwords" value="0" @if(old('expire_passwords', $setting->expire_passwords) == "0") checked @endif>
                                            <span><i></i>No</span>
                                        </label>
                                        @error('expire_passwords')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Social Media Links</h4>
                                    </div>
                                    <hr/>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="facebook" class="control-label">Facebook</label>
                                        <input type="url" name="facebook" value="{{old('facebook', $setting->facebook)}}" class="form-control">
                                        @error('facebook')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="linkedin" class="control-label">LinkedIn</label>
                                        <input type="url" name="linkedin" value="{{old('linkedin', $setting->linkedin)}}" class="form-control">
                                        @error('linkedin')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Instagram</label>
                                        <input type="url" name="instagram" value="{{old('instagram', $setting->instagram)}}" class="form-control">
                                        @error('instagram')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="twitter" class="control-label">Twitter</label>
                                        <input type="url" name="twitter" value="{{old('twitter', $setting->twitter)}}" class="form-control">
                                        @error('twitter')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label for="cover">Logo </label>
                                    <input type="file" name="logo" id="cover" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg" data-default-file="{{ asset("storage/$setting->logo") }}" >
                                    @if ($errors->has('logo'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('logo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="cover">Favicon </label>
                                    <input type="file" name="favicon" id="favicon" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg" data-default-file="{{ asset("storage/$setting->favicon") }}" >
                                    @if ($errors->has('favicon'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('favicon') }}</strong>
                                        </span>
                                    @endif
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
