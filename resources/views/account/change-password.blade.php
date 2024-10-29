@extends('layouts.main')
@section('page-title', 'Account')
@section('title', 'Change Password')
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Change Password</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-9 col-md-8 col-xl-6 col-xxl-4 m-t-35">
                            <form method="POST" action="{{ route('account.change-password') }}" class="needs-validation" novalidate>
                                @csrf
                                @method("PUT")
                                <div class="form-group">
                                    <label for="current-password" class="form-label">{{ __('Current Password') }}</label>
                                    <input id="current-password" type="password" class="form-control{{ $errors->has('current-password') ? ' is-invalid' : '' }}"
                                           name="current-password" value="{{ old('current-password') }}" required>
                                    @error('current-password')
                                    <div class="text-danger" role="alert">
                                {{ $message }}
                            </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new-password" class="form-label">{{ __('New Password') }}</label>
                                    <input id="new-password" type="password" minlength="6" data-minlength-error="Minimum of 6 characters allowed"
                                           class="form-control" name="new-password" required>
                                    @error('new-password')
                                    <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                                    <input id="password-confirm" data-match="#new-password" data-match-error="Passwords must match" type="password" required class="form-control" name="new-password_confirmation">
                                    @error('new-password_confirmation')
                                    <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Change Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
