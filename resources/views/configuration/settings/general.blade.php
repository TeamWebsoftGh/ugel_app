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
                    <form method="POST" action="{{route('configuration.settings.site.store')}}" enctype="multipart/form-data">
                        <p>All fields with <span class="text-danger">*</span> are required.</p>
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="general" value="1">
                        <div class="row clearfix">
                            <div class="col-md-8 col-lg-9">
                                <div class="row">
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="app_name" class="control-label">Application Name <span class="text-danger">*</span></label>
                                        <input type="text" id="app_name" name="app_name" value="{{ old_set('app_name', NULL, $rec) }}" class="form-control">
                                        @error('app_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control" value="{{ old_set('company_name', NULL, $rec) }}">
                                        @error('company_name')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Company Email</label>
                                        <input type="email" name="company_email" class="form-control" value="{{ old_set('company_email', NULL, $rec) }}">
                                        @error('company_email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Company Notification Email</label>
                                        <input type="email" name="company_notification_email" class="form-control" value="{{ old_set('company_notification_email', NULL, $rec) }}">
                                        @error('company_notification_email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Phone Number</label>
                                        <input type="tel" name="company_phone_number" value="{{ old_set('company_phone_number', NULL, $rec) }}" class="form-control">
                                        @error('company_phone_number')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Alternate Company Phone Number</label>
                                        <input type="tel" name="company_phone_number_alt" value="{{ old_set('company_phone_number_alt', NULL, $rec) }}" class="form-control">
                                        @error('company_phone_number_alt')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Address <span class="text-danger">*</span></label>
                                        <input type="text" name="company_address" value="{{ old_set('company_address', NULL, $rec) }}" class="form-control">
                                        @error('company_address')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="name" class="control-label">Company Address(Line 2)</label>
                                        <input type="tel" name="address_line_2" value="{{ old_set('address_line_2', NULL, $rec) }}" class="form-control">
                                        @error('address_line_2')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="google_map" class="control-label">Google Map</label>
                                        <input type="text" name="google_map" value="{{ old_set('google_map', NULL, $rec) }}" class="form-control">
                                        @error('google_map')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="description" class="control-label">Time zone</label>
                                        <select name="time_zone" class="selectpicker form-control" data-live-search="true" title="{{__('Time Zone')}}...">
                                            @foreach($zones_array as $zone)
                                                <option value="{{$zone['zone']}}" {{(old_set('time_zone', NULL, $rec) === $zone['zone']) ? "selected" : ''}} >{{$zone['diff_from_GMT'] . ' - ' . $zone['zone']}}</option>
                                            @endforeach
                                        </select>
                                        @error('time_zone')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="date_format" class="control-label">Date format</label>
                                        <select name="date_format" class="selectpicker form-control">
                                            @foreach($date_formats as $key => $date_format)
                                                <option value="{{$key}}" {{(old_set('date_format', NULL, $rec) === $key) ? "selected" : ''}} >{{$date_format}}</option>
                                            @endforeach
                                        </select>
                                        @error('date_format')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="email" class="control-label">Report Start Year</label>
                                        <input type="number" name="report_start_year" class="form-control" value="{{ old_set('report_start_year', NULL, $rec) }}">
                                        @error('report_start_year')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="vision" class="control-label">Vision</label>
                                        <textarea class="form-control" rows="6" name="vision">{{ old_set('vision', NULL, $rec) }}</textarea>
                                        @error('vision')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="mission" class="control-label">Mission</label>
                                        <textarea class="form-control" rows="6" name="mission">{{ old_set('mission', NULL, $rec) }}</textarea>
                                        @error('mission')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-xl-4">
                                        <label for="description" class="control-label">Description</label>
                                        <textarea class="form-control" rows="6" name="description">{{ old_set('description', NULL, $rec) }}</textarea>
                                        @error('description')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-3 col-xl-2">
                                        <label>Verify Emails</label>
                                        <br />
                                        <label class="fancy-radio">
                                            <input type="radio" name="verify_email" value="1" @if(old_set('verify_email', NULL, $rec) == "1") checked @endif>
                                            <span><i></i>Yes</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input type="radio" name="verify_email" value="0" @if(old_set('verify_email', NULL, $rec) == "0") checked @endif>
                                            <span><i></i>No</span>
                                        </label>
                                        @error('verify_email')
                                        <span class="input-note text-danger">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3 col-xl-2">
                                        <label>Expire Passwords</label>
                                        <br />
                                        <label class="fancy-radio">
                                            <input type="radio" name="expire_passwords" value="1" @if(old_set('expire_passwords', NULL, $rec) == "1") checked @endif>
                                            <span><i></i>Yes</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input type="radio" name="expire_passwords" value="0" @if(old_set('expire_passwords', NULL, $rec) == "0") checked @endif>
                                            <span><i></i>No</span>
                                        </label>
                                        @error('expire_passwords')
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
                                    <input type="file" name="logo" id="cover" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg" data-default-file="{{ asset(old_set('logo', NULL, $rec)) }}" >
                                    @if ($errors->has('logo'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('logo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="cover">Favicon </label>
                                    <input type="file" name="favicon" id="favicon" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg" data-default-file="{{ asset(old_set('favicon', NULL, $rec)) }}" >
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
